<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class AuthController extends Controller
{
    public function loginform(Request $request)
    {
        $data = [
            'pageTitle' => 'Login',
        ];
        return view('auth.login', $data);
    }

    public function registerform(Request $request)
    {
        $data = [
            'pageTitle' => 'Register',
        ];
        return view('auth.register', $data);
    }

    public function loginHandler(Request $request)
    {
        try {
            $request->validate([
                'login_id' => 'required|exists:users,username',
                'password' => 'required|min:4',
            ], [
                'login_id.exists'   => 'Username yang kamu masukkan tidak terdaftar.',
                'login_id.required' => 'Harap masukkan username kamu.',
                'password.required' => 'Harap masukkan password kamu.',
                'password.min'      => 'Password minimal 4 karakter.',
            ]);

            $credentials = [
                'username' => $request->login_id,
                'password' => $request->password,
            ];

            $remember = $request->has('remember');

            if (!Auth::attempt($credentials, $remember)) {
                return back()->withErrors([
                    'login_id' => 'Password salah. Silakan coba lagi.',
                ])->withInput($request->except('password'));
            }

            return redirect()->route('home');
        } catch (\Exception $e) {
            return back()->withErrors([
                'login_id' => $e->getMessage()
            ])->withInput($request->except('password'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function registerHandler(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:14|unique:users,username|alpha_num',
            'password' => 'required|min:6',
        ], [
            'name.required'     => 'Harap masukkan nama lengkap kamu.',
            'username.required' => 'Harap masukkan username kamu.',
            'username.unique'   => 'Username sudah digunakan, coba yang lain.',
            'username.alpha_num'=> 'Username hanya boleh mengandung huruf dan angka.',
            'username.max'      => 'Username maksimal 14 karakter.',
            'password.required' => 'Harap masukkan password kamu.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        Session::flash('success', 'Registrasi berhasil. Silakan login untuk mengakses akun Anda.');

        return redirect()->route('login');
    }
}