<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['code', 'name', 'category', 'purity', 'weight', 'size', 'model', 'price', 'stock', 'image'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function findByCode(string $code): ?array
    {
        return $this->where('code', $code)->first();
    }

    public function getLowStock(int $threshold = 3): array
    {
        return $this->where('stock <=', $threshold)->where('stock >', 0)->findAll();
    }

    public function getOutOfStock(): array
    {
        return $this->where('stock', 0)->findAll();
    }

    public function decreaseStock(int $id, int $quantity): bool
    {
        $product = $this->find($id);
        if (!$product || $product['stock'] < $quantity) {
            return false;
        }

        return $this->update($id, ['stock' => $product['stock'] - $quantity]);
    }

    public function increaseStock(int $id, int $quantity): bool
    {
        $product = $this->find($id);
        if (!$product) {
            return false;
        }

        return $this->update($id, ['stock' => $product['stock'] + $quantity]);
    }
}
