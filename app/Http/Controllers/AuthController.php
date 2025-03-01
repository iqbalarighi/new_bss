<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function loginabsen(Request $request)
    {
       if(Auth::guard('karyawan')->attempt(['nip' => $request->nip, 'password' => $request->password])) {
        echo "masuk";
       } else {
        echo "gagal";
       }
}
}
