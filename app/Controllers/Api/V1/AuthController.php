<?php

namespace App\Controllers\Api\V1;

use App\Services\AuthService;
use App\Validation\AuthRules;

class AuthController extends BaseApiController
{
    protected AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function register()
    {
        $rules = AuthRules::register();

        if (!$this->validate($rules)) {
            return $this->respondValidationError($this->validator->getErrors());
        }

        try {
            $data = $this->request->getJSON(true);
            $result = $this->authService->register($data);

            return $this->respondCreated($result, 'Registrasi berhasil');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 500);
        }
    }

    public function login()
    {
        $rules = AuthRules::login();

        if (!$this->validate($rules)) {
            return $this->respondValidationError($this->validator->getErrors());
        }

        $data = $this->request->getJSON(true);
        $result = $this->authService->login($data['email'], $data['password']);

        if (!$result) {
            return $this->respondUnauthorized('Email atau password salah');
        }

        return $this->respondSuccess($result, 'Login berhasil');
    }

    public function me()
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->respondUnauthorized();
        }

        return $this->respondSuccess([
            'id' => $user->id,
            'name' => $user->name ?? '',
            'email' => $user->email,
            'role' => $user->role,
        ]);
    }

    public function logout()
    {
        return $this->respondNoContent('Logout berhasil');
    }

    public function changePassword()
    {
        $rules = AuthRules::changePassword();

        if (!$this->validate($rules)) {
            return $this->respondValidationError($this->validator->getErrors());
        }

        $data = $this->request->getJSON(true);
        $userId = $this->getUserId();

        $success = $this->authService->changePassword(
            $userId,
            $data['current_password'],
            $data['new_password']
        );

        if (!$success) {
            return $this->respondFail('Password lama salah', 400);
        }

        return $this->respondNoContent('Password berhasil diubah');
    }
}
