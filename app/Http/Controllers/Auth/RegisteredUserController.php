<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        return view('auth.register');
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
            'village' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:admin,petani'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $isAdmin = $request->role === 'admin';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'village' => $request->village,
            'role' => $request->role,
            'account_status' => $isAdmin ? 'pending' : 'active',
            'approved_at' => $isAdmin ? null : now(),
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        ActivityLog::record(
            $isAdmin ? 'admin_registered_pending' : 'farmer_registered',
            $isAdmin
                ? "{$user->name} mendaftar sebagai admin dan menunggu persetujuan."
                : "{$user->name} mendaftar sebagai petani.",
            $user,
            ['role' => $user->role, 'account_status' => $user->account_status],
            $user,
            $request
        );

        Auth::login($user);

        return redirect($user->dashboardRoute());
    }
}
