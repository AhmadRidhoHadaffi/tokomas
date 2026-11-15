<?php

namespace App\Services;

use App\Exceptions\OtpException;
use App\Models\OtpCodeModel;
use Config\Otp as OtpConfig;

class OtpService
{
    public const PURPOSE_REGISTER = 'register';
    public const PURPOSE_RESET = 'reset_password';

    protected OtpConfig $config;
    protected OtpCodeModel $model;

    public function __construct(?OtpConfig $config = null)
    {
        $this->config = $config ?? config('Otp');
        $this->model = new OtpCodeModel();
    }

    /**
     * Terbitkan kode OTP baru untuk pengguna.
     *
     * @return array{code:string, expires_at:string, phone:string}
     */
    public function issue(int $userId, string $phone, string $purpose): array
    {
        $phone = self::normalizePhone($phone);

        $last = $this->model
            ->where('user_id', $userId)
            ->where('purpose', $purpose)
            ->orderBy('id', 'DESC')
            ->first();

        if ($last && ! empty($last['created_at'])) {
            $availableAt = strtotime($last['created_at']) + (int) $this->config->resendCooldown;
            $remaining = $availableAt - time();
            if ($remaining > 0) {
                throw new OtpException('Tunggu ' . $remaining . ' detik sebelum meminta kode baru.');
            }
        }

        $this->model->invalidateAll($userId, $purpose);

        $code = $this->generateCode();
        $now = date('Y-m-d H:i:s');
        $expiresAt = date('Y-m-d H:i:s', time() + ((int) $this->config->ttlMinutes * 60));

        $this->model->insert([
            'user_id'      => $userId,
            'phone'        => $phone,
            'purpose'      => $purpose,
            'code_hash'    => $this->hash($code, $purpose),
            'attempts'     => 0,
            'expires_at'   => $expiresAt,
            'last_sent_at' => $now,
            'consumed_at'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        $this->deliver($phone, $code, $purpose);

        return [
            'code'       => $code,
            'expires_at' => $expiresAt,
            'phone'      => $phone,
        ];
    }

    /**
     * Verifikasi kode OTP. Melempar OtpException bila gagal.
     */
    public function verify(int $userId, string $purpose, string $code): void
    {
        $code = preg_replace('/\D+/', '', $code);
        if ($code === '') {
            throw new OtpException('Kode OTP wajib diisi.');
        }

        $otp = $this->model->latestActive($userId, $purpose);
        if (! $otp) {
            throw new OtpException('Kode OTP tidak ditemukan. Silakan minta kode baru.');
        }

        if (strtotime((string) $otp['expires_at']) < time()) {
            throw new OtpException('Kode OTP sudah kedaluwarsa. Silakan minta kode baru.');
        }

        if ((int) $otp['attempts'] >= (int) $this->config->maxAttempts) {
            throw new OtpException('Terlalu banyak percobaan. Silakan minta kode baru.');
        }

        if (! hash_equals((string) $otp['code_hash'], $this->hash($code, $purpose))) {
            $this->model->update($otp['id'], ['attempts' => (int) $otp['attempts'] + 1]);
            $left = (int) $this->config->maxAttempts - ((int) $otp['attempts'] + 1);
            throw new OtpException('Kode OTP salah. Sisa percobaan: ' . max($left, 0) . '.');
        }

        $this->model->update($otp['id'], ['consumed_at' => date('Y-m-d H:i:s')]);
    }

    public function secondsUntilResend(int $userId, string $purpose): int
    {
        $last = $this->model
            ->where('user_id', $userId)
            ->where('purpose', $purpose)
            ->orderBy('id', 'DESC')
            ->first();

        if (! $last || empty($last['created_at'])) {
            return 0;
        }

        return max(0, strtotime((string) $last['created_at']) + (int) $this->config->resendCooldown - time());
    }

    /**
     * Kode ditampilkan di UI hanya saat bukan production agar mudah diuji.
     */
    public function shouldRevealCode(): bool
    {
        return ENVIRONMENT !== 'production';
    }

    public static function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if ($digits === '') {
            return '';
        }
        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }
        if (str_starts_with($digits, '8')) {
            return '62' . $digits;
        }
        return $digits;
    }

    /**
     * Varian nomor untuk pencarian di database (simpanan bisa 08xx atau 628xx).
     *
     * @return list<string>
     */
    public static function phoneVariants(string $phone): array
    {
        $digits = preg_replace('/\D+/', '', $phone);
        $normalized = self::normalizePhone($phone);
        $local = str_starts_with($normalized, '62') ? '0' . substr($normalized, 2) : $normalized;

        return array_values(array_unique(array_filter([$phone, $digits, $normalized, $local])));
    }

    public static function maskPhone(string $phone): string
    {
        $normalized = self::normalizePhone($phone);
        if (strlen($normalized) < 8) {
            return $normalized;
        }

        return substr($normalized, 0, 4) . str_repeat('*', max(strlen($normalized) - 7, 2)) . substr($normalized, -3);
    }

    protected function generateCode(): string
    {
        $length = max(4, (int) $this->config->codeLength);
        $max = (int) (10 ** $length) - 1;

        return str_pad((string) random_int(0, $max), $length, '0', STR_PAD_LEFT);
    }

    protected function hash(string $code, string $purpose): string
    {
        $key = $this->config->hashKey;
        if ($key === '') {
            $key = (string) (config('Encryption')->key ?? '');
        }
        if ($key === '') {
            $key = 'toko-mas-gembira-otp';
        }

        return hash_hmac('sha256', $purpose . ':' . $code, $key);
    }

    protected function deliver(string $phone, string $code, string $purpose): void
    {
        $label = $purpose === self::PURPOSE_RESET ? 'reset kata sandi' : 'verifikasi pendaftaran';
        $message = 'Kode OTP Toko Mas Gembira untuk ' . $label . ': ' . $code
            . '. Berlaku ' . (int) $this->config->ttlMinutes . ' menit. Jangan bagikan kode ini.';

        if ($this->config->channel === 'fonnte' && $this->config->fonnteToken !== '') {
            try {
                $client = \Config\Services::curlrequest();
                $client->post('https://api.fonnte.com/send', [
                    'headers' => ['Authorization' => $this->config->fonnteToken],
                    'form_params' => ['target' => $phone, 'message' => $message],
                    'timeout' => 10,
                    'http_errors' => false,
                ]);
            } catch (\Throwable $e) {
                log_message('error', 'Gagal mengirim OTP via Fonnte: {message}', ['message' => $e->getMessage()]);
            }
        }

        log_message('info', 'OTP {purpose} untuk {phone}: {code}', [
            'purpose' => $purpose,
            'phone'   => $phone,
            'code'    => $code,
        ]);
    }
}
