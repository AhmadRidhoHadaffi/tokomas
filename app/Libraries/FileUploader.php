<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;

class FileUploader
{
    protected array $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    protected int $maxSize = 2097152; // 2MB in bytes

    public function upload(UploadedFile $file, string $path): ?string
    {
        if (!$file->isValid()) {
            throw new \Exception('File tidak valid');
        }

        if (!in_array($file->getMimeType(), $this->allowedTypes)) {
            throw new \Exception('Tipe file tidak diizinkan. Hanya JPEG, PNG, WEBP');
        }

        if ($file->getSize() > $this->maxSize) {
            throw new \Exception('Ukuran file maksimal 2MB');
        }

        $newName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $file->getExtension();
        
        if (!$file->move($path, $newName)) {
            throw new \Exception('Gagal upload file');
        }

        return $newName;
    }

    public function delete(string $path, string $filename): bool
    {
        $fullPath = $path . '/' . $filename;
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }
}
