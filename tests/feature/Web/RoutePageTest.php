<?php

namespace Tests\Feature\Web;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

final class RoutePageTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testProtectedRolePagesRedirectGuests(): void
    {
        foreach (['/owner', '/owner/transaksi', '/owner/pembukuan', '/owner/pesanan-custom', '/owner/pelanggan', '/owner/promosi', '/owner/karyawan', '/owner/laporan', '/owner/pengaturan', '/karyawan', '/karyawan/transaksi', '/karyawan/pembukuan', '/karyawan/pesanan-custom', '/karyawan/pelanggan', '/karyawan/promosi', '/pelanggan', '/pelanggan/pesanan', '/pelanggan/riwayat', '/pelanggan/notifikasi', '/pelanggan/profil'] as $path) {
            $this->get($path)->assertRedirectTo('/masuk');
        }
    }

    public function testAllRoleGetPagesDoNotReturnServerError(): void
    {
        $routes = [
            'owner' => ['/owner', '/owner/produk', '/owner/produk/baru', '/owner/transaksi', '/owner/transaksi/baru', '/owner/pembukuan', '/owner/pembukuan/baru', '/owner/pesanan-custom', '/owner/pesanan-custom/baru', '/owner/pelanggan', '/owner/promosi', '/owner/promosi/baru', '/owner/karyawan', '/owner/karyawan/baru', '/owner/laporan', '/owner/pengaturan'],
            'karyawan' => ['/karyawan', '/karyawan/produk', '/karyawan/transaksi', '/karyawan/transaksi/baru', '/karyawan/pembukuan', '/karyawan/pembukuan/baru', '/karyawan/pesanan-custom', '/karyawan/pesanan-custom/baru', '/karyawan/pelanggan', '/karyawan/promosi'],
            'pelanggan' => ['/pelanggan', '/pelanggan/pesanan', '/pelanggan/pesanan/baru', '/pelanggan/riwayat', '/pelanggan/notifikasi', '/pelanggan/profil'],
        ];
        foreach ($routes as $role => $paths) {
            foreach ($paths as $path) {
                $response = $this->withSession(['user_id' => 1, 'role' => $role])->get($path);
                $this->assertNotSame(500, $response->response()->getStatusCode(), $role . ' ' . $path);
            }
        }
    }
}
