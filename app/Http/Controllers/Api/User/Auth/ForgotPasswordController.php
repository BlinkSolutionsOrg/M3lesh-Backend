<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * Password recovery via a short emailed code.
 *
 * NOTE: actual delivery depends on the mailer (currently MAIL_MAILER=log, so the
 * code is written to the log). Configure SMTP for the email to reach the user.
 */
class ForgotPasswordController extends Controller
{
    private const TTL_MINUTES = 15;

    private function key(string $email): string
    {
        return 'pwreset:'.$email;
    }

    public function sendCode(Request $request): JsonResponse
    {
        $validated = $request->validate(['email' => ['required', 'email']]);
        $email = mb_strtolower(trim($validated['email']));

        $user = User::query()->where('email', $email)->first();
        if ($user === null) {
            throw ValidationException::withMessages([
                'email' => ['مفيش حساب بالإيميل ده 🤍'],
            ]);
        }

        $code = (string) random_int(10000, 99999);
        Cache::put($this->key($email), $code, now()->addMinutes(self::TTL_MINUTES));

        Mail::raw(
            "كود استعادة كلمة السر بتاعك في معلش: $code\n"
                .'الكود صالح لمدة '.self::TTL_MINUTES.' دقيقة. لو مش إنت اللي طلبته، تجاهل الرسالة دي.',
            function ($message) use ($email) {
                $message->to($email)->subject('استعادة كلمة السر — معلش');
            },
        );

        return response()->json([
            'message' => 'بعتنا كود الاستعادة على إيميلك 🤍',
        ]);
    }

    public function reset(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        $email = mb_strtolower(trim($validated['email']));

        $stored = Cache::get($this->key($email));
        if ($stored === null || ! hash_equals($stored, $validated['code'])) {
            throw ValidationException::withMessages([
                'code' => ['الكود غلط أو انتهت صلاحيته — اطلب كود جديد'],
            ]);
        }

        $user = User::query()->where('email', $email)->first();
        if ($user === null) {
            throw ValidationException::withMessages([
                'email' => ['مفيش حساب بالإيميل ده 🤍'],
            ]);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        Cache::forget($this->key($email));
        // Drop existing sessions so the new password is required everywhere.
        $user->tokens()->delete();

        return response()->json([
            'message' => 'اتغيّرت كلمة السر بنجاح 🤍 سجّل دخولك بالكلمة الجديدة',
        ]);
    }
}
