<?php

namespace App\Services;

use CodeIgniter\HTTP\Files\UploadedFile;

class UploadService
{
    protected const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    protected const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    /**
     * Store an uploaded image under public/uploads/{folder} and return its filename.
     *
     * Returns null when no file was uploaded.
     */
    public function storeImage(?UploadedFile $file, string $folder, int $maxKilobytes = 2048): ?string
    {
        if (!$file instanceof UploadedFile || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (!$file->isValid()) {
            throw new \RuntimeException('Berkas gambar tidak valid: ' . $file->getErrorString());
        }

        $extension = strtolower($file->getClientExtension() ?: $file->guessExtension() ?: '');
        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            throw new \RuntimeException('Format gambar harus JPG, PNG, atau WEBP');
        }

        if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES, true)) {
            throw new \RuntimeException('Tipe berkas gambar tidak didukung');
        }

        if ($file->getSize() > $maxKilobytes * 1024) {
            throw new \RuntimeException('Ukuran gambar maksimal ' . $maxKilobytes . ' KB');
        }

        $targetDirectory = ROOTPATH . 'public/uploads/' . trim($folder, '/');
        if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0755, true) && !is_dir($targetDirectory)) {
            throw new \RuntimeException('Folder unggahan tidak dapat dibuat');
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        if (!$file->move($targetDirectory, $filename)) {
            throw new \RuntimeException('Gambar gagal disimpan');
        }

        return $filename;
    }

    /**
     * Remove a previously stored image, ignoring missing or unsafe paths.
     */
    public function deleteImage(string $folder, ?string $filename): void
    {
        if ($filename === null || $filename === '' || basename($filename) !== $filename) {
            return;
        }

        $path = ROOTPATH . 'public/uploads/' . trim($folder, '/') . '/' . $filename;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
