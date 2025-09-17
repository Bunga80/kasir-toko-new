<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
        'username' => ['required'],
        'password' => ['required']
    ]);

    $user = User::where('username', $request->username)->first();
    
    // Username tidak ditemukan
    if (!$user) {
        return back()->withErrors([
            'username' => 'Username yang anda masukan salah.'
        ])->onlyInput('username');
    }

    // Password salah
    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors([
            'password' => 'Password yang anda masukan salah.'
        ])->onlyInput('username');
    }

    Auth::login($user, $request->remember);
    return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
