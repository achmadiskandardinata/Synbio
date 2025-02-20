<?php

namespace App\Http\Controllers\Frontends;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use function Laravel\Prompts\password;


class AuthController extends Controller
{
    public function login()
    {
        return view('frontends.auth.login');
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
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $remember = $request->has('remember');
            auth()->guard('web')->login($user, $remember);

            if ($remember) {
                $user->forceFill([
                    'remember_token' => Str::random(60),
                ])->save();
            }

            toastr()
                ->positionClass('toast-top-center')
                ->success('Login berhasil!');

            return redirect()->route('home');
        } else {
            if (!$user) {
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

    public function register(Request $request)
    {
        return view('frontends.auth.register');
    }

    public function register_post(Request $request)
    {
        // dd($request->all());
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|numeric|unique:users,phone',
                'password' => 'required|min:8|confirmed',

            ],
            [
                'name.required' => 'Nama tidak boleh kosong!',
                'email.required' => 'Email tidak boleh kosong!',
                'email.email' => 'Email tidak valid!',
                'email.unique' => 'Email sudah terdaftar!',
                'phone.required' => 'Nomor telepon tidak boleh kosong!',
                'phone.numeric' => 'Nomor telepon harus angka!',
                'phone.unique' => 'Nomor telepon sudah terdaftar!',
                'password.required' => 'Password tidak boleh kosong!',
                'password.min' => 'Password minimal 8 karakter!',
                'password.confirmed' => 'Password tidak sama!'
            ]
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            Auth::guard('web')->login($user);
            toastr()
                ->positionClass('toast-top-center')
                ->success('Registrasi berhasil! Silahkan login!');
            return redirect()->route('login');
        } else {
            toastr()
                ->positionClass('toast-top-center')
                ->error('Registrasi gagal! Silahkan coba lagi!');
            return redirect()->back();
        }
    }

    public function forgotPassword(Request $request)
    {
        return view('frontends.auth.forgotPassword');
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

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = Password::getRepository()->create($user);
            $email = $request->only('email')['email'];
            $url = URL::to('/resetPassword/' . $token . '?email=' . urlencode($email));
            $expiresAt = config('auth.passwords.users.expire');


            Mail::send('emails.email', ['url' => $url, 'expiresAt' => $expiresAt], function ($message) use ($email) {
                $message->to($email);
                $message->subject('Atur Ulang Password Akun');
            });

            toastr()
                ->positionClass('toast-top-center')
                ->success('Email reset password berhasil dikirim! Silahkan cek email anda!');
            return redirect()->route('login');
        }

        toastr()
            ->positionClass('toast-top-center')
            ->error('Email tidak terdaftar!');
        return redirect()->back();
    }

    public function resetPassword(Request $request)
    {
        return view('frontends.auth.resetPassword', [
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

        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(PasswordReset::class, $user);
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            toastr()
                ->positionClass('toast-top-center')
                ->success('Password berhasil direset! Silahkan login!');
            return redirect()->route('login');
        }
        toastr()
            ->positionClass('toast-top-center')
            ->error('Token tidak valid atau sudah expired!');
        return redirect()->back();
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        toastr()
            ->positionClass('toast-top-right')
            ->success('Logout berhasil!');
        return redirect('/');
    }
}
