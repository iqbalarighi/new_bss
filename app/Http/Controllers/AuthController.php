<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.pegawai-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('pegawai')->attempt($request->only('nip', 'password'))) {
            return redirect()->intended('/absen');
        }

        return back()->withErrors([
            'nip' => 'NIP atau password salah.',
        ]);
    }

    public function logout()
    {
        Auth::guard('pegawai')->logout();
        return redirect('/pegawai/login');
    }

}
