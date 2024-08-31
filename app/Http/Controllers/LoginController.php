<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('token');

        $pat = PersonalAccessToken::findToken($credentials['token']);

        if ($pat == null)
            return back()->withErrors([]);

        /** @var User $user */
        $user = $pat->tokenable()->first();

        Auth::login($user, true);

        if (Auth::check()) {
            $request->session()->regenerate();

            return redirect()->intended('');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

}
