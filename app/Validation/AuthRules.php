<?php

namespace App\Validation;

class AuthRules
{
    public static function register(): array
    {
        return [
            'name' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama wajib diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter',
                ],
            ],
            'email' => [
                'rules' => 'required|valid_email|max_length[100]|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email wajib diisi',
                    'valid_email' => 'Email tidak valid',
                    'max_length' => 'Email maksimal 100 karakter',
                    'is_unique' => 'Email sudah terdaftar',
                ],
            ],
            'phone' => [
                'rules' => 'required|min_length[10]|max_length[15]|is_unique[customers.phone]',
                'errors' => [
                    'required' => 'Nomor HP wajib diisi',
                    'min_length' => 'Nomor HP minimal 10 digit',
                    'max_length' => 'Nomor HP maksimal 15 digit',
                    'is_unique' => 'Nomor HP sudah terdaftar',
                ],
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Kata sandi wajib diisi',
                    'min_length' => 'Kata sandi minimal 8 karakter',
                ],
            ],
            'password_confirm' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi kata sandi wajib diisi',
                    'matches' => 'Konfirmasi kata sandi tidak cocok',
                ],
            ],
            'address' => [
                'rules' => 'permit_empty|max_length[200]',
                'errors' => [
                    'max_length' => 'Alamat maksimal 200 karakter',
                ],
            ],
        ];
    }

    public static function login(): array
    {
        return [
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email wajib diisi',
                    'valid_email' => 'Email tidak valid',
                ],
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password wajib diisi',
                ],
            ],
        ];
    }

    public static function changePassword(): array
    {
        return [
            'current_password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password lama wajib diisi',
                ],
            ],
            'new_password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password baru wajib diisi',
                    'min_length' => 'Password baru minimal 6 karakter',
                ],
            ],
        ];
    }

    public static function verifyOtp(): array
    {
        return [
            'code' => [
                'rules' => 'required|min_length[4]|max_length[8]',
                'errors' => [
                    'required' => 'Kode OTP wajib diisi',
                    'min_length' => 'Kode OTP tidak valid',
                    'max_length' => 'Kode OTP tidak valid',
                ],
            ],
        ];
    }

    public static function forgotPassword(): array
    {
        return [
            'identifier' => [
                'rules' => 'required|min_length[6]|max_length[100]',
                'errors' => [
                    'required' => 'Email atau nomor HP wajib diisi',
                    'min_length' => 'Email atau nomor HP terlalu pendek',
                    'max_length' => 'Email atau nomor HP terlalu panjang',
                ],
            ],
        ];
    }

    public static function resetPassword(): array
    {
        return [
            'code' => [
                'rules' => 'required|min_length[4]|max_length[8]',
                'errors' => [
                    'required' => 'Kode OTP wajib diisi',
                    'min_length' => 'Kode OTP tidak valid',
                    'max_length' => 'Kode OTP tidak valid',
                ],
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Kata sandi baru wajib diisi',
                    'min_length' => 'Kata sandi baru minimal 8 karakter',
                ],
            ],
            'password_confirm' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi kata sandi wajib diisi',
                    'matches' => 'Konfirmasi kata sandi tidak cocok',
                ],
            ],
        ];
    }
}
