<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Pengaturan kode OTP untuk verifikasi nomor HP dan reset kata sandi.
 */
class Otp extends BaseConfig
{
    /**
     * Panjang kode OTP (digit).
     */
    public int $codeLength = 6;

    /**
     * Masa berlaku kode dalam menit.
     */
    public int $ttlMinutes = 10;

    /**
     * Jumlah percobaan verifikasi maksimal sebelum kode dinyatakan gagal.
     */
    public int $maxAttempts = 5;

    /**
     * Jeda minimal (detik) sebelum kode baru boleh dikirim ulang.
     */
    public int $resendCooldown = 60;

    /**
     * Kanal pengiriman: "log" (default, ditulis ke log aplikasi) atau "fonnte".
     */
    public string $channel = 'log';

    /**
     * Token API Fonnte bila channel = "fonnte".
     */
    public string $fonnteToken = '';

    /**
     * Kunci HMAC untuk menyimpan hash kode OTP.
     * Biarkan kosong untuk memakai encryption.key aplikasi.
     */
    public string $hashKey = '';
}
