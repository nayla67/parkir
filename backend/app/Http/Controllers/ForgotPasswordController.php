<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * ==================================================
     * STEP 1
     * CEK EMAIL + KIRIM OTP WHATSAPP
     * ==================================================
     */

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);


        // ==================================================
        // RAPKAN EMAIL
        // ==================================================

        $email = strtolower(
            trim($request->email)
        );


        // ==================================================
        // CARI USER DI DATABASE
        // ==================================================

        $user = User::where('email', $email)->first();


        if (!$user) {

            return response()->json([
                'success' => false,
                'message' =>
                    'Email tidak terdaftar.'
            ], 404);
        }


        // ==================================================
        // GENERATE OTP 6 DIGIT
        // ==================================================

        $otp = random_int(
            100000,
            999999
        );


        // ==================================================
        // AMBIL NOMOR WHATSAPP DARI DATA USER
        // ==================================================

        $nomor = trim(
            $user->no_hp ?? ''
        );


        if (!$nomor) {

            Log::error(
                'USER TIDAK PUNYA NOMOR WHATSAPP',
                ['email' => $email]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'Akun ini belum memiliki nomor WhatsApp terdaftar. Hubungi Super Admin.'
            ], 500);
        }


        // ==================================================
        // NORMALISASI NOMOR INDONESIA
        // ==================================================

        if (str_starts_with($nomor, '0')) {

            $nomor =
                '62' .
                substr($nomor, 1);
        }


        // ==================================================
        // PESAN WHATSAPP
        // ==================================================

        $message =
            "🅿️ PARKIR PLAZA ANDALAS\n\n" .
            "Kode OTP untuk reset password {$user->name} adalah:\n\n" .
            "*{$otp}*\n\n" .
            "Kode OTP berlaku selama 5 menit.\n" .
            "Jangan berikan kode ini kepada siapapun.";


        // ==================================================
        // AMBIL KONFIGURASI FONNTE
        // ==================================================

        $fonnteToken =
            config('services.fonnte.token');

        $fonnteUrl =
            config('services.fonnte.url');


        if (!$fonnteToken) {

            Log::error('FONNTE TOKEN TIDAK DITEMUKAN');

            return response()->json([
                'success' => false,
                'message' =>
                    'Token Fonnte belum dikonfigurasi.'
            ], 500);
        }


        if (!$fonnteUrl) {

            Log::error('FONNTE URL TIDAK DITEMUKAN');

            return response()->json([
                'success' => false,
                'message' =>
                    'URL Fonnte belum dikonfigurasi.'
            ], 500);
        }


        // ==================================================
        // KIRIM OTP KE FONNTE
        // ==================================================

        try {

            $response = Http::withHeaders([
                'Authorization' => $fonnteToken,
            ])
                ->timeout(30)
                ->post(
                    $fonnteUrl,
                    [
                        'target' => $nomor,
                        'message' => $message,
                        'countryCode' => '62',
                    ]
                );


            if (!$response->successful()) {

                Log::error(
                    'FONNTE GAGAL MENGIRIM OTP',
                    [
                        'status' => $response->status(),
                        'response' => $response->body(),
                    ]
                );

                return response()->json([
                    'success' => false,
                    'message' =>
                        'OTP gagal dikirim ke WhatsApp.',
                ], 500);
            }


            // ==================================================
            // SIMPAN OTP KE CACHE (5 MENIT)
            // ==================================================

            Cache::put(
                'forgot_password_otp_' . $email,
                Hash::make($otp),
                now()->addMinutes(5)
            );


            return response()->json([
                'success' => true,
                'message' =>
                    'OTP berhasil dikirim ke WhatsApp.'
            ]);


        } catch (\Throwable $e) {

            Log::error('ERROR FONNTE', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' =>
                    'Terjadi kesalahan saat menghubungi Fonnte.',
            ], 500);
        }
    }


    /**
     * ==================================================
     * STEP 2
     * VERIFIKASI OTP
     * ==================================================
     */

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);


        $email = strtolower(trim($request->email));


        $user = User::where('email', $email)->first();

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' =>
                    'Email tidak terdaftar.'
            ], 404);
        }


        $storedOtp = Cache::get(
            'forgot_password_otp_' . $email
        );


        if (!$storedOtp) {

            return response()->json([
                'success' => false,
                'message' =>
                    'OTP sudah kadaluarsa. Silakan minta OTP baru.'
            ], 400);
        }


        if (!Hash::check($request->otp, $storedOtp)) {

            return response()->json([
                'success' => false,
                'message' =>
                    'OTP salah.'
            ], 400);
        }


        Cache::forget(
            'forgot_password_otp_' . $email
        );


        $resetToken = Str::random(64);


        Cache::put(
            'forgot_password_verified_' . $email,
            $resetToken,
            now()->addMinutes(10)
        );


        return response()->json([
            'success' => true,
            'message' =>
                'OTP benar. Silakan buat password baru.',
            'reset_token' => $resetToken,
        ]);
    }


    /**
     * ==================================================
     * STEP 3
     * RESET PASSWORD (LANGSUNG UPDATE KE DATABASE)
     * ==================================================
     */

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'reset_token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);


        $email = strtolower(trim($request->email));


        $user = User::where('email', $email)->first();

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' =>
                    'Email tidak terdaftar.'
            ], 404);
        }


        $storedToken = Cache::get(
            'forgot_password_verified_' . $email
        );


        if (
            !$storedToken ||
            !hash_equals($storedToken, $request->reset_token)
        ) {

            return response()->json([
                'success' => false,
                'message' =>
                    'Akses reset password tidak valid atau sudah kadaluarsa.'
            ], 400);
        }


        // ==================================================
        // UPDATE PASSWORD LANGSUNG DI DATABASE
        // ==================================================

        $user->update([
            'password' => Hash::make($request->password),
        ]);


        Cache::forget(
            'forgot_password_verified_' . $email
        );


        return response()->json([
            'success' => true,
            'message' =>
                'Password berhasil diubah. Silakan login dengan password baru.',
        ]);
    }
}