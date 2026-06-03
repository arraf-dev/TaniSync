<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\HarvestLog;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function dashboard(): View
    {
        return view('platform.dashboard', [
            'pageTitle' => 'Dashboard platform',
            'metrics' => [
                ['label' => 'Organisasi aktif', 'value' => Organization::where('status', 'active')->count(), 'detail' => 'Workspace berjalan', 'icon' => 'domain', 'tone' => 'primary'],
                ['label' => 'Menunggu approval', 'value' => Organization::where('status', 'pending')->count(), 'detail' => 'Pengajuan baru', 'icon' => 'pending_actions', 'tone' => 'warning'],
                ['label' => 'Total petani', 'value' => User::where('role', 'petani')->count(), 'detail' => 'Lintas organisasi', 'icon' => 'groups', 'tone' => 'success'],
                ['label' => 'Panen tercatat', 'value' => number_format((float) HarvestLog::sum('quantity'), 2, ',', '.').' kg', 'detail' => HarvestLog::count().' catatan', 'icon' => 'analytics', 'tone' => 'accent'],
            ],
            'pendingOrganizations' => Organization::with('admins')
                ->where('status', 'pending')
                ->latest()
                ->take(6)
                ->get(),
            'recentOrganizations' => Organization::latest()->take(5)->get(),
            'recentActivities' => ActivityLog::with(['user', 'organization'])->latest()->take(8)->get(),
        ]);
    }

    public function organizations(): View
    {
        return view('platform.organizations', [
            'pageTitle' => 'Manajemen organisasi',
            'organizations' => Organization::withCount([
                'users',
                'users as farmers_count' => fn ($query) => $query->where('role', 'petani'),
                'users as admins_count' => fn ($query) => $query->where('role', 'admin'),
                'harvestLogs',
            ])
                ->when(request('status'), function ($query, string $status): void {
                    if (in_array($status, ['pending', 'active', 'rejected'], true)) {
                        $query->where('status', $status);
                    }
                })
                ->when(request('search'), function ($query, string $search): void {
                    $query->where(function ($query) use ($search): void {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('region', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%");
                    });
                })
                ->latest()
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function approveOrganization(Request $request, Organization $organization): RedirectResponse
    {
        if (! $organization->isPending() && ! $organization->isRejected()) {
            abort(404);
        }

        $organization->update([
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => $request->user()->id,
            'rejected_at' => null,
        ]);

        User::where('organization_id', $organization->id)
            ->where('role', 'admin')
            ->whereIn('account_status', ['pending', 'rejected'])
            ->update([
                'account_status' => 'active',
                'approved_at' => now(),
                'approved_by' => $request->user()->id,
                'rejected_at' => null,
            ]);

        ActivityLog::record(
            'organization_approved',
            "{$request->user()->name} menyetujui organisasi {$organization->name}.",
            $organization,
            ['organization_id' => $organization->id],
            $request->user(),
            $request
        );

        return redirect()->route('platform.organizations')->with('status', 'Organisasi berhasil disetujui.');
    }

    public function rejectOrganization(Request $request, Organization $organization): RedirectResponse
    {
        if (! $organization->isPending()) {
            abort(404);
        }

        $organization->update([
            'status' => 'rejected',
            'approved_at' => null,
            'approved_by' => null,
            'rejected_at' => now(),
        ]);

        User::where('organization_id', $organization->id)
            ->where('role', 'admin')
            ->where('account_status', 'pending')
            ->update([
                'account_status' => 'rejected',
                'approved_at' => null,
                'approved_by' => null,
                'rejected_at' => now(),
            ]);

        ActivityLog::record(
            'organization_rejected',
            "{$request->user()->name} menolak organisasi {$organization->name}.",
            $organization,
            ['organization_id' => $organization->id],
            $request->user(),
            $request
        );

        return redirect()->route('platform.organizations')->with('status', 'Organisasi berhasil ditolak.');
    }
}
