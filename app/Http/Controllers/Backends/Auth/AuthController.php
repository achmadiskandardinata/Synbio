<?php

namespace App\Http\Controllers\Backends\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function login()
    {
        return view('backends.auth.login');
    }

    public function Login_post(Request $request)
    {
        // dd($request->all());

        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|min:8'
            ],
            [
                'email.required' => 'Email tidak boleh kosong!',
                'email.email' => 'Email tidak valid!',
                'password.required' => 'Password tidak boleh kosong!',
                'password.min' => 'Password  minimal 8 karakter!'
            ]
        );

        //fungsi
        $admin = Admin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            $remember = $request->has('remember');
            auth()->guard('admin')->login($admin, $remember);

            if ($remember) {
                $admin->forceFill([
                    'remember_token' => Str::random(60),
                ])->save();
            }

            toastr()
                ->positionClass('toast-top-center')
                ->success('Login berhasil!');

            return redirect()->route('admin.dashboard');
        } else {
            if (!$admin) {
                toastr()
                    ->positionClass('toast-top-center')
                    ->error('Email tidak terdaftar!');
                return redirect()->back();
            } else {
                toastr()
                    ->positionClass('toast-top-center')
                    ->error('Password tidak valid!');
                return redirect()->back();
            }
        }
    }

    public function forgotPassword(Request $request)
    {
        return view('backends.auth.forgotPassword');
    }

    public function forgotPassword_post(Request $request)
    {
        // dd($request->all());
        $request->validate(
            [
                'email' => 'required|email'
            ],
            [
                'email.required' => 'Email tidak boleh kosong!',
                'email.email' => 'Email tidak valid!',
            ]
        );

        $admin = Admin::where('email', $request->email)->first();

        if ($admin) {
            $token = Password::getRepository()->create($admin);
            $email = $request->only('email')['email'];
            $url = URL::to('/admin/resetPassword/' . $token . '?email=' . urlencode($email));
            $expiresAt = config('auth.passwords.admins.expire');


            Mail::send('emails.email', ['url' => $url, 'expiresAt' => $expiresAt], function ($message) use ($email) {
                $message->to($email);
                $message->subject('Atur Ulang Password Akun');
            });

            toastr()
                ->positionClass('toast-top-center')
                ->success('Email reset password berhasil dikirim! Silahkan cek email anda!');
            return redirect()->route('admin.login');
        }

        toastr()
            ->positionClass('toast-top-center')
            ->error('Email tidak terdaftar!');
        return redirect()->back();
    }

    public function resetPassword(Request $request)
    {
        return view('backends.auth.resetPassword', [
            'request' => $request
        ]);
    }

    public function resetPassword_post(Request $request)
    {
        $request->validate(
            [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed'
            ],
            [
                'token.required' => 'Token tidak boleh kosong!',
                'email.required' => 'Email tidak boleh kosong!',
                'email.email' => 'Email tidak valid!',
                'password.required' => 'Password tidak boleh kosong!',
                'password.min' => 'Password minimal 8 karakter!',
                'password.confirmed' => 'Password tidak sama!'
            ]
        );

        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Admin $admin) use ($request) {
                $admin->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(PasswordReset::class, $admin);
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            toastr()
                ->positionClass('toast-top-center')
                ->success('Password berhasil direset! Silahkan login!');
            return redirect()->route('admin.login');
        }
        toastr()
            ->positionClass('toast-top-center')
            ->error('Token tidak valid atau sudah expired!');
        return redirect()->back();
    }


    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        toastr()
            ->positionClass('toast-top-center')
            ->success('Logout berhasil!');
        return redirect()->route('admin.login');
    }
}
