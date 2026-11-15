<?php

namespace App\Models;

use CodeIgniter\Model;

class OtpCodeModel extends Model
{
    protected $table = 'otp_codes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id', 'phone', 'purpose', 'code_hash', 'attempts',
        'expires_at', 'last_sent_at', 'consumed_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function latestActive(int $userId, string $purpose): ?array
    {
        return $this->where('user_id', $userId)
            ->where('purpose', $purpose)
            ->where('consumed_at', null)
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function invalidateAll(int $userId, string $purpose): void
    {
        $this->where('user_id', $userId)
            ->where('purpose', $purpose)
            ->where('consumed_at', null)
            ->set(['consumed_at' => date('Y-m-d H:i:s')])
            ->update();
    }
}
