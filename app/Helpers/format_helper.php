<?php

/**
 * Format number as Indonesian Rupiah
 * @param float|int $amount
 * @param bool $showPrefix Include "Rp" prefix
 * @return string
 */
function rupiah($amount, bool $showPrefix = true): string
{
    $formatted = number_format((float) $amount, 0, ',', '.');
    return $showPrefix ? 'Rp ' . $formatted : $formatted;
}

/**
 * Format date to Indonesian format
 * @param string $date Date string
 * @param string $format 'short' (d/m/Y), 'medium' (d M Y), 'long' (d F Y)
 * @return string
 */
function format_date(string $date, string $format = 'medium'): string
{
    if (empty($date) || $date === '0000-00-00' || $date === '0000-00-00 00:00:00') {
        return '-';
    }

    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return $date;
    }

    $monthsShort = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $monthsFull = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    $d = date('j', $timestamp);
    $m = (int) date('n', $timestamp);
    $y = date('Y', $timestamp);

    return match ($format) {
        'short' => date('d/m/Y', $timestamp),
        'long' => $d . ' ' . $monthsFull[$m - 1] . ' ' . $y,
        default => $d . ' ' . $monthsShort[$m - 1] . ' ' . $y,
    };
}

/**
 * Format datetime to Indonesian format
 * @param string $datetime
 * @param bool $showSeconds
 * @return string
 */
function format_datetime(string $datetime, bool $showSeconds = false): string
{
    if (empty($datetime) || $datetime === '0000-00-00 00:00:00') {
        return '-';
    }

    $timestamp = strtotime($datetime);
    if ($timestamp === false) {
        return $datetime;
    }

    $date = format_date($datetime, 'medium');
    $time = $showSeconds ? date('H:i:s', $timestamp) : date('H:i', $timestamp);

    return $date . ' ' . $time;
}

/**
 * Format number with decimals
 * @param float|int $number
 * @param int $decimals
 * @return string
 */
function format_number($number, int $decimals = 0): string
{
    return number_format((float) $number, $decimals, ',', '.');
}

/**
 * Format weight in grams
 * @param float|int $grams
 * @return string
 */
function format_weight($grams): string
{
    return format_number($grams, 2) . ' gram';
}

/**
 * Format percentage
 * @param float|int $value
 * @param int $decimals
 * @return string
 */
function format_percent($value, int $decimals = 0): string
{
    return format_number($value, $decimals) . '%';
}

/**
 * Relative time (e.g., "2 hari lalu")
 * @param string $datetime
 * @return string
 */
function time_ago(string $datetime): string
{
    $timestamp = strtotime($datetime);
    if ($timestamp === false) {
        return $datetime;
    }

    $diff = time() - $timestamp;

    if ($diff < 60) {
        return 'Baru saja';
    }
    if ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' menit lalu';
    }
    if ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' jam lalu';
    }
    if ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' hari lalu';
    }

    return format_date($datetime, 'medium');
}
