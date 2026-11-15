<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\Exceptions\OtpException;
use App\Services\AuthService;
use App\Services\OtpService;
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
        $auth = new AuthService();
        $result = $auth->login($data['email'], $data['password']);
        if (! $result) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah, atau akun tidak aktif.');
        }

        if (! empty($result['unverified'])) {
            $userId = (int) $result['user']['id'];
            $phone = (string) ($auth->phoneForUser($userId) ?? '');
            $this->queueOtpSession($userId, $phone, OtpService::PURPOSE_REGISTER);

            $message = 'Akun Anda belum terverifikasi. Masukkan kode OTP yang dikirim ke nomor terdaftar.';
            try {
                $otp = (new OtpService())->issue($userId, $phone, OtpService::PURPOSE_REGISTER);
                $this->storeDebugCode($otp);
            } catch (OtpException $e) {
                $message = 'Akun Anda belum terverifikasi. ' . $e->getMessage();
            }

            return redirect()->to('/verifikasi-otp')->with('info', $message);
        }

        $this->startSession($result['user']);

        return redirect()->to($this->dashboardFor($result['user']['role']))
            ->with('success', 'Selamat datang, ' . $result['user']['name'] . '.');
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

        $data = $this->request->getPost();

        try {
            $result = (new AuthService())->register($data);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        $userId = (int) $result['user']['id'];
        $phone = (string) $data['phone'];
        $this->queueOtpSession($userId, $phone, OtpService::PURPOSE_REGISTER);

        $message = 'Registrasi berhasil. Kode OTP telah dikirim ke ' . OtpService::maskPhone($phone) . '.';
        try {
            $otp = (new OtpService())->issue($userId, $phone, OtpService::PURPOSE_REGISTER);
            $this->storeDebugCode($otp);
        } catch (OtpException $e) {
            $message = 'Registrasi berhasil, tetapi kode OTP gagal dikirim. ' . $e->getMessage();
        }

        return redirect()->to('/verifikasi-otp')->with('success', $message);
    }

    public function verifyOtpForm()
    {
        $pending = $this->session->get('otp_pending');
        if (! $pending) {
            return redirect()->to('/daftar')->with('error', 'Silakan daftar terlebih dahulu.');
        }

        return view('pages/public/verify_otp', [
            'phone'         => OtpService::maskPhone((string) $pending['phone']),
            'purpose'       => $pending['purpose'],
            'cooldown'      => (new OtpService())->secondsUntilResend((int) $pending['user_id'], (string) $pending['purpose']),
            'debugCode'     => $this->session->getFlashdata('otp_debug'),
        ]);
    }

    public function verifyOtp()
    {
        if (! $this->validate(AuthRules::verifyOtp())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $pending = $this->session->get('otp_pending');
        if (! $pending) {
            return redirect()->to('/daftar')->with('error', 'Sesi verifikasi berakhir. Silakan daftar ulang.');
        }

        try {
            (new OtpService())->verify((int) $pending['user_id'], (string) $pending['purpose'], (string) $this->request->getPost('code'));
        } catch (OtpException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $auth = new AuthService();
        $auth->markPhoneVerified((int) $pending['user_id']);

        $user = $auth->findUserByIdentifier((string) $pending['phone']) ?? [];
        if (! $user) {
            $this->session->remove('otp_pending');
            return redirect()->to('/masuk')->with('success', 'Nomor HP berhasil diverifikasi. Silakan masuk.');
        }

        $this->session->remove('otp_pending');
        $this->startSession([
            'id'    => (int) $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ]);

        return redirect()->to($this->dashboardFor($user['role']))
            ->with('success', 'Nomor HP berhasil diverifikasi. Selamat datang, ' . $user['name'] . '.');
    }

    public function resendOtp()
    {
        $pending = $this->session->get('otp_pending');
        if (! $pending) {
            return redirect()->to('/daftar')->with('error', 'Sesi verifikasi berakhir. Silakan daftar ulang.');
        }

        try {
            $otp = (new OtpService())->issue((int) $pending['user_id'], (string) $pending['phone'], (string) $pending['purpose']);
            $this->storeDebugCode($otp);
            return redirect()->to('/verifikasi-otp')
                ->with('success', 'Kode OTP baru telah dikirim ke ' . OtpService::maskPhone((string) $pending['phone']) . '.');
        } catch (OtpException $e) {
            return redirect()->to('/verifikasi-otp')->with('error', $e->getMessage());
        }
    }

    public function forgotPasswordForm()
    {
        return view('pages/public/forgot_password');
    }

    public function forgotPassword()
    {
        if (! $this->validate(AuthRules::forgotPassword())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $identifier = (string) $this->request->getPost('identifier');
        $auth = new AuthService();
        $user = $auth->findUserByIdentifier($identifier);

        if (! $user || (int) $user['is_active'] !== 1) {
            return redirect()->back()->withInput()
                ->with('error', 'Data akun tidak ditemukan. Periksa kembali email atau nomor HP Anda.');
        }

        $phone = $auth->phoneForUser((int) $user['id']);
        if (! $phone) {
            return redirect()->back()->withInput()
                ->with('error', 'Akun ini belum memiliki nomor HP terdaftar. Hubungi admin toko untuk bantuan.');
        }

        $this->session->set('reset_pending', [
            'user_id' => (int) $user['id'],
            'phone'   => (string) $phone,
        ]);

        $message = 'Kode OTP untuk reset kata sandi telah dikirim ke ' . OtpService::maskPhone((string) $phone) . '.';
        try {
            $otp = (new OtpService())->issue((int) $user['id'], (string) $phone, OtpService::PURPOSE_RESET);
            $this->storeDebugCode($otp);
        } catch (OtpException $e) {
            $message = 'Kode OTP belum dapat dikirim. ' . $e->getMessage();
        }

        return redirect()->to('/reset-sandi')->with('success', $message);
    }

    public function resetPasswordForm()
    {
        $pending = $this->session->get('reset_pending');
        if (! $pending) {
            return redirect()->to('/lupa-sandi')->with('error', 'Silakan masukkan email atau nomor HP terlebih dahulu.');
        }

        return view('pages/public/reset_password', [
            'phone'     => OtpService::maskPhone((string) $pending['phone']),
            'cooldown'  => (new OtpService())->secondsUntilResend((int) $pending['user_id'], OtpService::PURPOSE_RESET),
            'debugCode' => $this->session->getFlashdata('otp_debug'),
        ]);
    }

    public function resetPassword()
    {
        if (! $this->validate(AuthRules::resetPassword())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $pending = $this->session->get('reset_pending');
        if (! $pending) {
            return redirect()->to('/lupa-sandi')->with('error', 'Sesi reset kata sandi berakhir. Silakan mulai lagi.');
        }

        $post = $this->request->getPost();

        try {
            (new OtpService())->verify((int) $pending['user_id'], OtpService::PURPOSE_RESET, (string) $post['code']);
        } catch (OtpException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        (new AuthService())->updatePassword((int) $pending['user_id'], (string) $post['password']);
        $this->session->remove('reset_pending');

        return redirect()->to('/masuk')
            ->with('success', 'Kata sandi berhasil diperbarui. Silakan masuk dengan kata sandi baru.');
    }

    public function resendResetOtp()
    {
        $pending = $this->session->get('reset_pending');
        if (! $pending) {
            return redirect()->to('/lupa-sandi')->with('error', 'Sesi reset kata sandi berakhir. Silakan mulai lagi.');
        }

        try {
            $otp = (new OtpService())->issue((int) $pending['user_id'], (string) $pending['phone'], OtpService::PURPOSE_RESET);
            $this->storeDebugCode($otp);
            return redirect()->to('/reset-sandi')
                ->with('success', 'Kode OTP baru telah dikirim ke ' . OtpService::maskPhone((string) $pending['phone']) . '.');
        } catch (OtpException $e) {
            return redirect()->to('/reset-sandi')->with('error', $e->getMessage());
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/masuk')->with('success', 'Anda sudah keluar.');
    }

    private function queueOtpSession(int $userId, string $phone, string $purpose): void
    {
        $this->session->set('otp_pending', [
            'user_id' => $userId,
            'phone'   => $phone,
            'purpose' => $purpose,
        ]);
    }

    private function storeDebugCode(array $otp): void
    {
        if ((new OtpService())->shouldRevealCode()) {
            $this->session->setFlashdata('otp_debug', $otp['code']);
        }
    }

    private function startSession(array $user): void
    {
        $this->session->regenerate(true);
        $this->session->set([
            'user_id'       => (int) $user['id'],
            'user_name'     => $user['name'],
            'user_email'    => $user['email'],
            'role'          => $user['role'],
            'is_logged_in'  => true,
        ]);
    }

    private function dashboardFor(string $role): string
    {
        return match ($role) {
            'owner' => '/owner',
            'karyawan' => '/karyawan',
            default => '/pelanggan',
        };
    }
}
