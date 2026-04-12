<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Admin;
use App\Models\OtpCode;
use App\Mail\OtpMail;
use Exception;
use PragmaRX\Google2FA\Google2FA;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->intended('/dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            $admin = Admin::where('email', Auth::user()->email)
                         ->where('status', 1)
                         ->where('is_deleted', 0)
                         ->first();

            if (!$admin) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda tidak memiliki akses ke sistem ini.']);
            }

            session(['2fa_admin_id' => $admin->id]);

            if (!$admin->two_factor_secret) {
                return redirect()->route('2fa.setup');
            }

            Auth::logout();
            return redirect()->route('2fa.choose');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Login Google gagal, silakan coba lagi.');
        }

        $admin = Admin::where('email', $googleUser->getEmail())
                     ->where('status', 1)
                     ->where('is_deleted', 0)
                     ->first();

        if (!$admin) {
            return redirect('/login')->with('error', 'Email ini tidak memiliki akses ke sistem.');
        }

        $user = User::where('google_id', $googleUser->getId())->first();
        if (!$user) {
            $user = User::where('email', $googleUser->getEmail())->first();
            if ($user) {
                $user->update(['google_id' => $googleUser->getId()]);
            } else {
                $user = User::create([
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password'  => bcrypt(str()->random(24)),
                ]);
            }
        }

        if (!$admin->two_factor_secret) {
            Auth::login($user, true);
            session(['2fa_admin_id' => $admin->id]);
            return redirect()->route('2fa.setup');
        }

        session([
            '2fa_admin_id' => $admin->id,
            '2fa_user_id'  => $user->id,
        ]);

        return redirect()->route('2fa.choose');
    }

    // ── Halaman pilih metode 2FA ──
    public function show2FAChoose()
    {
        if (!session('2fa_admin_id')) return redirect('/login');
        return view('auth.2fa-choose');
    }

    // ── Setup Google Authenticator ──
    public function show2FASetup()
    {
        $adminId = session('2fa_admin_id');
        if (!$adminId) return redirect('/login');

        $admin     = Admin::findOrFail($adminId);
        $google2fa = new Google2FA();

        if (!$admin->two_factor_secret) {
            $admin->update(['two_factor_secret' => $google2fa->generateSecretKey()]);
        }

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $admin->email,
            $admin->two_factor_secret
        );

        return view('auth.2fa-setup', compact('qrCodeUrl', 'admin'));
    }

    // ── Verifikasi Google Authenticator ──
    public function show2FAVerify()
    {
        if (!session('2fa_admin_id')) return redirect('/login');
        return view('auth.2fa-verify');
    }

    public function verify2FA(Request $request)
    {
        $request->validate(['one_time_password' => 'required|digits:6']);

        $adminId = session('2fa_admin_id');
        $userId  = session('2fa_user_id');
        if (!$adminId) return redirect('/login');

        $admin     = Admin::findOrFail($adminId);
        $google2fa = new Google2FA();

        if (!$google2fa->verifyKey($admin->two_factor_secret, $request->one_time_password)) {
            return back()->withErrors(['one_time_password' => 'Kode OTP salah atau sudah kadaluarsa.']);
        }

        $this->loginUser($adminId, $userId, $request);
        return redirect()->intended('/dashboard');
    }

    // ── Kirim Email OTP ──
    public function sendEmailOtp()
    {
        $adminId = session('2fa_admin_id');
        if (!$adminId) return redirect('/login');

        $admin = Admin::findOrFail($adminId);

        // Hapus OTP lama
        OtpCode::where('admin_id', $adminId)->delete();

        // Generate OTP baru
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'admin_id'   => $adminId,
            'code'       => $code,
            'expires_at' => now()->addMinutes(5),
            'used'       => false,
            'created_at' => now(),
        ]);

        // Kirim email
        Mail::to($admin->email)->send(new OtpMail($code, $admin->name));

        return redirect()->route('2fa.email.verify');
    }

    // ── Halaman verifikasi Email OTP ──
    public function showEmailOtpVerify()
    {
        if (!session('2fa_admin_id')) return redirect('/login');
        return view('auth.2fa-email-verify');
    }

    // ── Proses verifikasi Email OTP ──
    public function verifyEmailOtp(Request $request)
    {
        $request->validate(['otp_code' => 'required|digits:6']);

        $adminId = session('2fa_admin_id');
        $userId  = session('2fa_user_id');
        if (!$adminId) return redirect('/login');

        $otp = OtpCode::where('admin_id', $adminId)
                      ->where('code', $request->otp_code)
                      ->where('used', false)
                      ->where('expires_at', '>', now())
                      ->first();

        if (!$otp) {
            return back()->withErrors(['otp_code' => 'Kode OTP salah atau sudah kadaluarsa.']);
        }

        $otp->update(['used' => true]);
        $this->loginUser($adminId, $userId, $request);
        return redirect()->intended('/dashboard');
    }

    // ── Helper login user ──
    private function loginUser($adminId, $userId, $request)
    {
        $admin = Admin::findOrFail($adminId);
        if ($userId) {
            $user = User::findOrFail($userId);
        } else {
            $user = User::where('email', $admin->email)->firstOrFail();
        }

        Auth::login($user, true);
        session()->forget(['2fa_admin_id', '2fa_user_id']);
        $request->session()->regenerate();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}