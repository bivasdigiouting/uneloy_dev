<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TwoFactorOtp extends Model
{
    protected $fillable = [
        'context',
        'subject_id',
        'email',
        'otp_code',
        'otp_hash',
        'attempts',
        'last_sent_at',
        'expires_at',
        'consumed_at',
    ];

    protected $casts = [
        'last_sent_at' => 'datetime',
        'expires_at' => 'datetime',
        'consumed_at' => 'datetime',
    ];

    public static function issue(string $context, int $subjectId, string $email, int $ttlMinutes = 10): array
    {
        $plainOtp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $storePlain = app()->environment('local') || (bool) config('twofa.store_plain_otp', false);

        self::query()
            ->where('context', $context)
            ->where('subject_id', $subjectId)
            ->whereNull('consumed_at')
            ->delete();

        $otp = self::query()->create([
            'context' => $context,
            'subject_id' => $subjectId,
            'email' => $email,
            'otp_code' => $storePlain ? $plainOtp : null,
            'otp_hash' => Hash::make($plainOtp),
            'attempts' => 0,
            'last_sent_at' => now(),
            'expires_at' => now()->addMinutes($ttlMinutes),
            'consumed_at' => null,
        ]);

        return [$otp, $plainOtp];
    }

    public static function verifyAndConsume(int $otpId, string $context, string $plainOtp, int $maxAttempts = 5): array
    {
        return DB::transaction(function () use ($otpId, $context, $plainOtp, $maxAttempts) {
            $otp = self::query()
                ->where('id', $otpId)
                ->where('context', $context)
                ->lockForUpdate()
                ->first();

            if (! $otp) {
                return ['success' => false, 'message' => 'OTP not found. Please login again.'];
            }

            if ($otp->consumed_at) {
                return ['success' => false, 'message' => 'OTP already used. Please login again.'];
            }

            if (now()->greaterThan($otp->expires_at)) {
                return ['success' => false, 'message' => 'OTP expired. Please resend OTP.'];
            }

            if ((int) $otp->attempts >= $maxAttempts) {
                return ['success' => false, 'message' => 'Too many attempts. Please resend OTP.'];
            }

            $isValid = Hash::check($plainOtp, (string) $otp->otp_hash);
            if (! $isValid && is_string($otp->otp_code) && $otp->otp_code !== '') {
                $isValid = hash_equals($otp->otp_code, $plainOtp);
            }
            if (! $isValid) {
                $otp->attempts = (int) $otp->attempts + 1;
                $otp->save();
                return ['success' => false, 'message' => 'Invalid OTP.'];
            }

            $otp->consumed_at = now();
            $otp->otp_code = null;
            $otp->save();

            return ['success' => true, 'message' => 'OTP verified'];
        });
    }

    public function canResend(int $cooldownSeconds = 60): bool
    {
        if (! $this->last_sent_at) {
            return true;
        }
        return now()->diffInSeconds($this->last_sent_at) >= $cooldownSeconds;
    }
}
