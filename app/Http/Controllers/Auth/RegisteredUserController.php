<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'organizations' => Organization::active()->orderBy('name')->get(),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'in:admin,petani'],
            'organization_id' => [
                Rule::requiredIf($request->role === 'petani'),
                'nullable',
                Rule::exists('organizations', 'id')->where('status', 'active'),
            ],
            'organization_name' => [Rule::requiredIf($request->role === 'admin'), 'nullable', 'string', 'max:255'],
            'organization_type' => [Rule::requiredIf($request->role === 'admin'), 'nullable', Rule::in(['desa', 'gapoktan', 'koperasi', 'komunitas', 'lainnya'])],
            'region' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $isAdmin = $request->role === 'admin';

        $user = DB::transaction(function () use ($request, $isAdmin): User {
            $organization = $isAdmin
                ? Organization::create([
                    'name' => $request->string('organization_name')->toString(),
                    'slug' => $this->uniqueOrganizationSlug($request->string('organization_name')->toString()),
                    'type' => $request->string('organization_type')->toString(),
                    'region' => $request->string('region')->toString() ?: null,
                    'address' => $request->string('address')->toString() ?: null,
                    'status' => 'pending',
                ])
                : Organization::active()->findOrFail($request->integer('organization_id'));

            return User::create([
                'name' => $request->name,
                'email' => $request->email,
                'organization_id' => $organization->id,
                'village' => $organization->name,
                'role' => $request->role,
                'account_status' => $isAdmin ? 'pending' : 'active',
                'approved_at' => $isAdmin ? null : now(),
                'password' => Hash::make($request->password),
            ]);
        });

        event(new Registered($user));

        ActivityLog::record(
            $isAdmin ? 'organization_requested' : 'farmer_registered',
            $isAdmin
                ? "{$user->name} mengajukan organisasi {$user->organization?->name} dan menunggu persetujuan."
                : "{$user->name} mendaftar sebagai petani.",
            $user,
            ['role' => $user->role, 'account_status' => $user->account_status, 'organization_id' => $user->organization_id],
            $user,
            $request
        );

        Auth::login($user);

        return redirect($user->dashboardRoute());
    }

    private function uniqueOrganizationSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'organisasi';
        $slug = $base;
        $suffix = 2;

        while (Organization::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
