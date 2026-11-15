<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Dilempar ketika proses OTP gagal (kedaluwarsa, salah, cooldown, dsb).
 */
class OtpException extends RuntimeException
{
}
