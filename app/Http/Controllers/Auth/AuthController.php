<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view("auth.index");
    }

    public function loginProses(Request $request)
    {
        $request->validate([
            "login_id" => "required",
            "password"  => "required",
        ], [
            "login_id.required" => "Please fill out this field",
            "password.required" => "Please fill out this field",
        ]);

        $login = $request->login_id;
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? "email" : "login_id";

        if (Auth::attempt([$field => $login, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            $nama = Auth::user()->name;
            // return redirect()->route('dashboard')->with('success', "Halo {$nama}! Login berhasil, selamat datang di SILA-PTK");
        }

        return back()->withErrors([
            'login_id' => 'Login gagal, silahkan cek kembali User ID dan Password',
        ])->withInput($request->only('login_id', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
