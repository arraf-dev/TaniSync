<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AccountStatusController extends Controller
{
    public function pending(Request $request): View
    {
        return view('account.pending', [
            'user' => $request->user(),
        ]);
    }
}
