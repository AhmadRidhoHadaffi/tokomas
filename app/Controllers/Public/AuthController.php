<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\Services\AuthService;
use App\Validation\AuthRules;

class AuthController extends BaseController
{
    public function loginForm()
    {
        return view('pages/public/login');
    }

    public function login()
    {
        if (! $this->validate(AuthRules::login())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();
        $result = (new AuthService())->login($data['email'], $data['password']);
        if (! $result) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah, atau akun tidak aktif.');
        }

        $this->session->regenerate(true);
        $this->session->set([
            'user_id' => (int) $result['user']['id'],
            'user_name' => $result['user']['name'],
            'user_email' => $result['user']['email'],
            'role' => $result['user']['role'],
            'is_logged_in' => true,
        ]);

        return redirect()->to(match ($result['user']['role']) {
            'owner' => '/owner', 'karyawan' => '/karyawan', default => '/pelanggan',
        })->with('success', 'Selamat datang, ' . $result['user']['name'] . '.');
    }

    public function registerForm()
    {
        return view('pages/public/register');
    }

    public function register()
    {
        if (! $this->validate(AuthRules::register())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $result = (new AuthService())->register($this->request->getPost());
            $this->session->regenerate(true);
            $this->session->set([
                'user_id' => (int) $result['user']['id'], 'user_name' => $result['user']['name'],
                'user_email' => $result['user']['email'], 'role' => 'pelanggan', 'is_logged_in' => true,
            ]);
            return redirect()->to('/pelanggan')->with('success', 'Registrasi berhasil.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/masuk')->with('success', 'Anda sudah keluar.');
    }
}
