<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\str;
use App\Helpers\CMail;
use Illuminate\Support\Carbon;
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

    public function forgotForm(Request $request)
    {
        $data = [
            'pageTitle' => 'Forgot Password',
        ];
        return view('auth.forgot', $data);
    }

    public function loginHandler(Request $request)
    {
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
        try {
            if ($fieldType == 'email') {
                $request->validate([
                    'login_id' => 'required|email|exists:users,email',
                    'password' => 'required|min:4',
                ], [
                    'login_id.exists' => 'The email you entered is not registered.',
                    'login_id.required' => 'Please enter your email.',
                    'login_id.email' => 'Please enter a valid email address.',
                    'password.required' => 'Please enter your password.',
                    'password.min' => 'Password must be at least 4 characters.'
                ]);
            } else {
                $request->validate([
                    'login_id' => 'required|exists:users,username',
                    'password' => 'required|min:4',
                ], [
                    'login_id.exists' => 'The username you entered is not registered.',
                    'login_id.required' => 'Please enter your username.',
                    'password.required' => 'Please enter your password.',
                    'password.min' => 'Password must be at least 4 characters.'
                ]);
            }
    
            $credentials = [
                $fieldType => $request->login_id,
                'password' => $request->password
            ];
    
            $remember = $request->has('remember');
    
            if (!Auth::attempt($credentials, $remember)) {
                return back()->withErrors([
                    'login_id' => 'Wrong password. Please try again.',
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
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'username' => 'required|string|max:14|unique:users,username',
        'password' => 'required|min:6'
    ]);

    $token = Str::random(64);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'username' => $request->username,
        'password' => Hash::make($request->password),
    ]);

    Session::flash('success', 'Registrasi berhasil. Silakan login untuk mengakses akun Anda.');

    return redirect()->route('register');
}

    public function resetHandler(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'The email you entered is not registered.',
            'email.required' => 'Please enter your email.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        $user = User::where('email', $request->email)->first();
        $token = base64_encode(Str::random(64));
        $oldToken = DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->first();

        if ($oldToken) {
            DB::table('password_reset_tokens')
                ->where('email', $user->email)
                ->update([
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
        } else {
            DB::table('password_reset_tokens')
                ->insert([
                    'email' => $user->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
        }

        $actionLink = route('reset_password', ['token' => $token]);
        $data = array(
            'actionLink' => $actionLink,
            'user' => $user
        );

        $mail_body = view('email-template.forgot-template', $data)->render();
        $mailConfig = array(
            'recipeint_address' => $user->email,
            'recipeint_name' => $user->username,
            'subject' => 'Password Reset',
            'body' => $mail_body
        );

        if (CMail::send($mailConfig)) {
            return redirect()->route('forgot')->with('success', 'Reset password link has been sent to your email');
        } else {
            return redirect()->route('forgot')->with('fail', 'Failed to send reset password link');
        }
    }
    

public function showResetForm($token) 
{
    $tokenData = DB::table('password_reset_tokens')
        ->where('token', $token)
        ->first();

    if (!$tokenData) {
        return redirect()->route('forgot')
            ->with('fail', 'Invalid password reset token');
    }

    $createdAt = Carbon::parse($tokenData->created_at);
    if ($createdAt->addMinutes(30)->isPast()) {
        DB::table('password_reset_tokens')->where('token', $token)->delete();
        return redirect()->route('forgot')
            ->with('fail', 'Password reset token has expired');
    }

    return view('auth.reset', ['token' => $token]);
}

public function resetForm(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $tokenData = DB::table('password_reset_tokens')
        ->where('token', $request->token)
        ->where('email', $request->email)
        ->first();

    if (!$tokenData) {
        return back()->withErrors(['email' => 'Invalid token or email']);
    }

    $user = User::where('email', $request->email)->first();
    
    if (!$user) {
        return back()->withErrors(['email' => 'User not found']);
    }

    $user->password = Hash::make($request->password);
    $user->save();

    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return redirect()->route('login')
        ->with('success', 'Password has been successfully reset');
}
}
    