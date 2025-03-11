<?php

namespace App\Http\Controllers;

use App\Models\PegawaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.pegawai-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');
        
        $pegawai = PegawaiModel::where('nip', $request->nip)->first();

        if ($pegawai && Hash::check($request->password, $pegawai->password)) {
            if ($pegawai->status === 'Aktif') {
                Auth::guard('pegawai')->attempt($credentials, $remember);
                return redirect()->intended('/absen');
            } else {
                return back()->with('error', 'Akun Anda tidak aktif. Silakan hubungi admin.');
            }
        }

        return redirect()->back()->with('error', 'NIP atau Password salah!');
    }

    public function logout()
    {
        Auth::guard('pegawai')->logout();
        return redirect('/pegawai/login');
    }

}

