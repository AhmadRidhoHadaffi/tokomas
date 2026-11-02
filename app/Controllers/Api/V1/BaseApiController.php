<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;

class BaseApiController extends ResourceController
{
    protected $format = 'json';

    protected function respondSuccess($data = null, string $message = '', int $code = 200, array $meta = [])
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return $this->respond($response, $code);
    }

    protected function respondCreated($data = null, string $message = 'Data berhasil dibuat')
    {
        return $this->respondSuccess($data, $message, 201);
    }

    protected function respondNoContent(string $message = 'Berhasil')
    {
        return $this->respond(['success' => true, 'message' => $message], 200);
    }

    protected function respondFail(string $message, int $code = 400, array $errors = [])
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return $this->respond($response, $code);
    }

    protected function respondValidationError(array $errors)
    {
        return $this->respondFail('Validasi gagal', 422, $errors);
    }

    protected function respondUnauthorized(string $message = 'Unauthorized')
    {
        return $this->respondFail($message, 401);
    }

    protected function respondForbidden(string $message = 'Akses ditolak')
    {
        return $this->respondFail($message, 403);
    }

    protected function respondNotFound(string $message = 'Data tidak ditemukan')
    {
        return $this->respondFail($message, 404);
    }

    protected function getUser(): ?object
    {
        return $this->request->user ?? null;
    }

    protected function getUserId(): ?int
    {
        $user = $this->getUser();
        return $user->id ?? null;
    }

    protected function getUserRole(): ?string
    {
        $user = $this->getUser();
        return $user->role ?? null;
    }
}
