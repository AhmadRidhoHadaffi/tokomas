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
}
