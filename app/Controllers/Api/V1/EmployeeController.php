<?php

namespace App\Controllers\Api\V1;

use App\Services\EmployeeService;

class EmployeeController extends BaseApiController
{
    protected EmployeeService $employeeService;

    public function __construct()
    {
        $this->employeeService = new EmployeeService();
    }

    public function index()
    {
        $filters = ['search' => $this->request->getGet('search'), 'is_active' => $this->request->getGet('is_active'), 'page' => $this->request->getGet('page'), 'per_page' => $this->request->getGet('per_page')];
        $result = $this->employeeService->getAll($filters);
        return $this->respondSuccess($result['items'], '', 200, ['total' => $result['total'], 'page' => $result['page'], 'per_page' => $result['per_page'], 'total_pages' => $result['total_pages']]);
    }

    public function show($id = null)
    {
        $emp = $this->employeeService->getById($id);
        if (!$emp) return $this->respondNotFound();
        return $this->respondSuccess($emp);
    }

    public function create()
    {
        $data = $this->request->getJSON(true);
        try {
            $id = $this->employeeService->create($data);
            $emp = $this->employeeService->getById($id);
            return $this->respondCreated($emp, 'Karyawan berhasil ditambahkan');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 400);
        }
    }

    public function update($id = null)
    {
        if (!$this->employeeService->getById($id)) return $this->respondNotFound();
        $data = $this->request->getJSON(true);
        try {
            $this->employeeService->update($id, $data);
            return $this->respondSuccess($this->employeeService->getById($id), 'Data karyawan diperbarui');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 400);
        }
    }

    public function toggleActive($id = null)
    {
        if ($this->employeeService->toggleActive($id)) {
            return $this->respondSuccess($this->employeeService->getById($id), 'Status aktif diubah');
        }
        return $this->respondNotFound();
    }

    public function resetPassword($id = null)
    {
        $data = $this->request->getJSON(true);
        if (empty($data['new_password'])) return $this->respondFail('Password baru wajib diisi', 400);
        if ($this->employeeService->resetPassword($id, $data['new_password'])) {
            return $this->respondNoContent('Password berhasil direset');
        }
        return $this->respondNotFound();
    }
}
