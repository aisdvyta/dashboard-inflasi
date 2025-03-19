<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\role;
use App\Models\master_satker;
use App\Models\master_wilayah;
use App\Models\user;
use App\Models\master_komoditas;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Hapus semua data dari tabel
        role::truncate();
        master_satker::truncate();
        master_wilayah::truncate();
        master_komoditas::truncate();
        user::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        role::insert([
            ['id' => 1, 'nama_role' => 'Admin Provinsi'],
            ['id' => 2, 'nama_role' => 'Admin Kabkot'],
        ]);

        master_satker::insert([
            ['kode_satker' => 3500, 'nama_satker' => 'PROV JAWA TIMUR'],
            ['kode_satker' => 3504, 'nama_satker' => 'KAB TULUNGAGUNG'],
            ['kode_satker' => 3509, 'nama_satker' => 'JEMBER'],
            ['kode_satker' => 3510, 'nama_satker' => 'BANYUWANGI'],
            ['kode_satker' => 3522, 'nama_satker' => 'KAB BOJONEGORO'],
            ['kode_satker' => 3525, 'nama_satker' => 'KAB GRESIK'],
            ['kode_satker' => 3529, 'nama_satker' => 'SUMENEP'],
            ['kode_satker' => 3571, 'nama_satker' => 'KOTA KEDIRI'],
            ['kode_satker' => 3573, 'nama_satker' => 'KOTA MALANG'],
            ['kode_satker' => 3574, 'nama_satker' => 'KOTA PROBOLINGGO'],
            ['kode_satker' => 3577, 'nama_satker' => 'KOTA MADIUN'],
            ['kode_satker' => 3578, 'nama_satker' => 'KOTA SURABAYA'],
        ]);

        master_wilayah::insert([
            ['kode_wil' => 3500, 'nama_wil' => 'PROV JAWA TIMUR'],
            ['kode_wil' => 3504, 'nama_wil' => 'KAB TULUNGAGUNG'],
            ['kode_wil' => 3509, 'nama_wil' => 'JEMBER'],
            ['kode_wil' => 3510, 'nama_wil' => 'BANYUWANGI'],
            ['kode_wil' => 3522, 'nama_wil' => 'KAB BOJONEGORO'],
            ['kode_wil' => 3525, 'nama_wil' => 'KAB GRESIK'],
            ['kode_wil' => 3529, 'nama_wil' => 'SUMENEP'],
            ['kode_wil' => 3571, 'nama_wil' => 'KOTA KEDIRI'],
            ['kode_wil' => 3573, 'nama_wil' => 'KOTA MALANG'],
            ['kode_wil' => 3574, 'nama_wil' => 'KOTA PROBOLINGGO'],
            ['kode_wil' => 3577, 'nama_wil' => 'KOTA MADIUN'],
            ['kode_wil' => 3578, 'nama_wil' => 'KOTA SURABAYA'],
        ]);

        // Seed users
        user::insert([
            [
                'id' => (string) Str::uuid(),
                'id_satker' => 3500,
                'id_role' => 1,
                'nama' => 'Admin Prov',
                'email' => 'adminprov@example.com',
                'password' => Hash::make('1234'),
            ],
            [
                'id' => (string) Str::uuid(),
                'id_satker' => 3578,
                'id_role' => 2,
                'nama' => 'Admin Kabkot',
                'email' => 'adminkabkot@example.com',
                'password' => Hash::make('1234'),
            ],
        ]);

        master_komoditas::insert([
            ['kode_kom' => '0', 'nama_kom' => 'UMUM', 'flag' => 0],
            ['kode_kom' => '01', 'nama_kom' => 'MAKANAN, MINUMAN DAN TEMBAKAU', 'flag' => 1],
            ['kode_kom' => '011', 'nama_kom' => 'MAKANAN', 'flag' => 2],
            ['kode_kom' => '0111001', 'nama_kom' => 'BERAS', 'flag' => 3],
            ['kode_kom' => '0111002', 'nama_kom' => 'BERAS JAGUNG', 'flag' => 3],
            ['kode_kom' => '0111003', 'nama_kom' => 'KETAN', 'flag' => 3],
            ['kode_kom' => '0111004', 'nama_kom' => 'JAGUNG PIPIL', 'flag' => 3],
            ['kode_kom' => '0111005', 'nama_kom' => 'SINGKONG', 'flag' => 3],
            ['kode_kom' => '0111006', 'nama_kom' => 'GANYONG', 'flag' => 3],
            ['kode_kom' => '0111007', 'nama_kom' => 'SAGU', 'flag' => 3],
            ['kode_kom' => '0111008', 'nama_kom' => 'TEPUNG TERIGU', 'flag' => 3],
            ['kode_kom' => '0111009', 'nama_kom' => 'TEPUNG BERAS', 'flag' => 3],
            ['kode_kom' => '0111010', 'nama_kom' => 'TEPUNG KETAN', 'flag' => 3],
            ['kode_kom' => '0111011', 'nama_kom' => 'TEPUNG JAGUNG', 'flag' => 3],
            ['kode_kom' => '0111012', 'nama_kom' => 'TEPUNG TAPIOKA', 'flag' => 3],
            ['kode_kom' => '0111013', 'nama_kom' => 'BISKUIT', 'flag' => 3],
            ['kode_kom' => '0111014', 'nama_kom' => 'ROTI TAWAR', 'flag' => 3],
            ['kode_kom' => '0111015', 'nama_kom' => 'ROTI MANIS', 'flag' => 3],
            ['kode_kom' => '0111016', 'nama_kom' => 'MI BASAH', 'flag' => 3],
            ['kode_kom' => '0111017', 'nama_kom' => 'MI KERING', 'flag' => 3],
            ['kode_kom' => '0111018', 'nama_kom' => 'MI INSTAN', 'flag' => 3],
            ['kode_kom' => '0111019', 'nama_kom' => 'SEREAL', 'flag' => 3],
            ['kode_kom' => '0111020', 'nama_kom' => 'MAKARONI', 'flag' => 3],
            ['kode_kom' => '0111021', 'nama_kom' => 'KERUPUK', 'flag' => 3],
            ['kode_kom' => '0111022', 'nama_kom' => 'KACANG TANAH', 'flag' => 3],
            ['kode_kom' => '0111023', 'nama_kom' => 'KACANG HIJAU', 'flag' => 3],
            ['kode_kom' => '0111024', 'nama_kom' => 'KEDELAI', 'flag' => 3],
            ['kode_kom' => '0111025', 'nama_kom' => 'TAHU', 'flag' => 3],
            ['kode_kom' => '0111026', 'nama_kom' => 'TEMPE', 'flag' => 3],
            ['kode_kom' => '0111027', 'nama_kom' => 'ONCOM', 'flag' => 3],
            ['kode_kom' => '0111028', 'nama_kom' => 'LONTONG', 'flag' => 3],
            ['kode_kom' => '0111029', 'nama_kom' => 'KETUPAT', 'flag' => 3],
            ['kode_kom' => '0111030', 'nama_kom' => 'NASI', 'flag' => 3],
            ['kode_kom' => '0111031', 'nama_kom' => 'BUBUR', 'flag' => 3],
            ['kode_kom' => '0111032', 'nama_kom' => 'SATE', 'flag' => 3],
            ['kode_kom' => '0111033', 'nama_kom' => 'LONTONG SAYUR', 'flag' => 3],
            ['kode_kom' => '0111034', 'nama_kom' => 'MIE AYAM', 'flag' => 3],
            ['kode_kom' => '0111035', 'nama_kom' => 'NASI GORENG', 'flag' => 3],
            ['kode_kom' => '0111036', 'nama_kom' => 'GADO-GADO', 'flag' => 3],
            ['kode_kom' => '0111037', 'nama_kom' => 'KAREDOK', 'flag' => 3],
            ['kode_kom' => '0111038', 'nama_kom' => 'RUJAK', 'flag' => 3],
            ['kode_kom' => '0111039', 'nama_kom' => 'PEMPEK', 'flag' => 3],
            ['kode_kom' => '0111040', 'nama_kom' => 'BAKSO', 'flag' => 3],
            ['kode_kom' => '0111041', 'nama_kom' => 'SIOMAY', 'flag' => 3],
            ['kode_kom' => '0111042', 'nama_kom' => 'PASTA', 'flag' => 3],
            ['kode_kom' => '0111043', 'nama_kom' => 'PIZZA', 'flag' => 3],
            ['kode_kom' => '0111044', 'nama_kom' => 'BURGER', 'flag' => 3],
            ['kode_kom' => '0111045', 'nama_kom' => 'HOTDOG', 'flag' => 3],
            ['kode_kom' => '0111046', 'nama_kom' => 'KUE BASAH', 'flag' => 3],
            ['kode_kom' => '0111047', 'nama_kom' => 'KUE KERING', 'flag' => 3],
            ['kode_kom' => '0111048', 'nama_kom' => 'PERMEN', 'flag' => 3],
            ['kode_kom' => '0111049', 'nama_kom' => 'COKLAT', 'flag' => 3],
            ['kode_kom' => '0111050', 'nama_kom' => 'ICE CREAM', 'flag' => 3],
        ]);
    }
}
