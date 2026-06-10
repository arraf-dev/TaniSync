<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class SuperAdminController extends Controller
{
    public function __construct()
    {
    }

    /**
     * List all users (admin + petani).
     */
    public function users(): View
    {
        $users = User::query()
            ->orderByRaw("FIELD(role, 'superadmin', 'admin', 'petani')")
            ->orderBy('name')
            ->get();

        return view('superadmin.users', [
            'pageTitle' => 'Kelola pengguna sistem',
            'users' => $users,
        ]);
    }

    /**
     * Show form to create a new admin or petani user.
     */
    public function createUser(): View
    {
        return view('superadmin.user-create', [
            'pageTitle' => 'Tambah pengguna baru',
        ]);
    }

    /**
     * Store a new user (admin or petani).
     */
    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'village' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:admin,petani'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'village' => $validated['village'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        return redirect()
            ->route('superadmin.users')
            ->with('status', "Pengguna {$validated['name']} berhasil ditambahkan sebagai {$validated['role']}.");
    }

    /**
     * Delete a user (cannot delete superadmin or self).
     */
    public function deleteUser(Request $request, User $user): RedirectResponse
    {
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Akun SuperAdmin tidak dapat dihapus.');
        }

        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('superadmin.users')
            ->with('status', "Pengguna {$name} berhasil dihapus.");
    }
}
