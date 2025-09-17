<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'nama'=>'Pak Patah',
            'username'=>'admin',
            'role'=>'admin',
            'password'=>bcrypt('password'),
            ]);

        \App\Models\User::create([
            'nama'=>'Pak Aldhi',
            'username'=>'admin1',
            'role'=>'admin',
            'password'=>bcrypt('password'),
            ]);

        \App\Models\User::create([
            'nama'=>'Bunga',
            'username'=>'admin2',
            'role'=>'admin',
            'password'=>bcrypt('password'),
            ]);

        \App\Models\User::create([
            'nama'=>'Aulia',
            'username'=>'petugas',
            'role'=>'petugas',
            'password'=>bcrypt('123'),
            ]);

        \App\Models\User::create([
            'nama'=>'Desi',
            'username'=>'petugas1',
            'role'=>'petugas',
            'password'=>bcrypt('123'),
            ]);

        \App\Models\User::create([
            'nama'=>'Wanda',
            'username'=>'petugas2',
            'role'=>'petugas',
            'password'=>bcrypt('123'),
            ]);

        \App\Models\Pelanggan::create([
            'nama'=>'Zahra',
            'alamat'=>'Tunggilis',
            'nomor_tlp'=>'082288866677'
        ]);

        \App\Models\Pelanggan::create([
            'nama'=>'Dina',
            'alamat'=>'Kalipucang',
            'nomor_tlp'=>'082288866677'
        ]);

        \App\Models\Pelanggan::create([
            'nama'=>'Nabila',
            'alamat'=>'Sindangwangi',
            'nomor_tlp'=>'082288866677'
        ]);

        \App\Models\Pelanggan::create([
            'nama'=>'Satrio',
            'alamat'=>'Kalipucang',
            'nomor_tlp'=>'082288866677'
        ]);

        \App\Models\Pelanggan::create([
            'nama'=>'Hanifah',
            'alamat'=>'Kalipucang',
            'nomor_tlp'=>'082288866677'
        ]);

        \App\Models\Kategori::create([
            'nama_kategori'=>'Kesehatan',
        ]);

        \App\Models\Kategori::create([
            'nama_kategori'=>'Kecantikan',
        ]);

        \App\Models\Kategori::create([
            'nama_kategori'=>'Elektronik',
        ]);

        \App\Models\Produk::create([
            'kategori_id'=> 1,
            'kode_produk'=>'1001',
            'nama_produk'=>'Now Foods C-1000',
            'harga_produk' => 620000,    // Harga beli/modal
            'harga_jual' => 780000,      // Harga jual sebelum diskon
            'diskon' => 20,             // Tidak ada diskon
            'harga' => 624000,           // Harga final (5000 - 0% = 5000)
        ]);

        \App\Models\Produk::create([
            'kategori_id'=> 1,
            'kode_produk'=>'1002',
            'nama_produk'=>'Blackmores Bio C-1000',
            'harga_produk' => 419500,    // Harga beli/modal
            'harga_jual' => 420000,      // Harga jual sebelum diskon
            'diskon' => 0,             // Tidak ada diskon
            'harga' => 420000,           // Harga final (5000 - 0% = 5000)
        ]);

        \App\Models\Produk::create([
            'kategori_id'=> 1,
            'kode_produk'=>'1003',
            'nama_produk'=>'Nutrimax C+',
             'harga_produk' => 218000,    // Harga beli/modal
            'harga_jual' => 220000,      // Harga jual sebelum diskon
            'diskon' => 0,             // Tidak ada diskon
            'harga' => 220000,           // Harga final (5000 - 0% = 5000)
        ]);

        \App\Models\Produk::create([
            'kategori_id'=> 1,
            'kode_produk'=>'1004',
            'nama_produk'=>'Balincer Liposomal Vitamin C',
             'harga_produk' => 114000,    // Harga beli/modal
            'harga_jual' => 115000,      // Harga jual sebelum diskon
            'diskon' => 0,             // Tidak ada diskon
            'harga' => 115000,           // Harga final (5000 - 0% = 5000)
        ]);

        \App\Models\Produk::create([
            'kategori_id'=> 1,
            'kode_produk'=>'1005',
            'nama_produk'=>'Time Release Vitamin C',
             'harga_produk' => 198000,    // Harga beli/modal
            'harga_jual' => 199500,      // Harga jual sebelum diskon
            'diskon' => 0,             // Tidak ada diskon
            'harga' => 199500,           // Harga final (5000 - 0% = 5000)
        ]);

        \App\Models\Produk::create([
            'kategori_id'=> 2,
            'kode_produk'=>'1006',
            'nama_produk'=>'Foundation DIOR',
             'harga_produk' => 1200000,    // Harga beli/modal
            'harga_jual' => 1500000,      // Harga jual sebelum diskon
            'diskon' => 10,             // Tidak ada diskon
            'harga' => 1350000,           // Harga final (5000 - 0% = 5000)
        ]);

        \App\Models\Produk::create([
            'kategori_id'=> 2,
            'kode_produk'=>'1007',
            'nama_produk'=>'Lip Glow Lip Balm DIOR',
             'harga_produk' => 720000,    // Harga beli/modal
            'harga_jual' => 721500,      // Harga jual sebelum diskon
            'diskon' => 0,             // Tidak ada diskon
            'harga' => 721500,           // Harga final (5000 - 0% = 5000)
        ]);

        \App\Models\Produk::create([
            'kategori_id'=> 2,
            'kode_produk'=>'1008',
            'nama_produk'=>'Cushion Foundation DIOR',
             'harga_produk' => 900000,    // Harga beli/modal
            'harga_jual' => 902000,      // Harga jual sebelum diskon
            'diskon' => 0,             // Tidak ada diskon
            'harga' => 902000,           // Harga final (5000 - 0% = 5000)
        ]);

        \App\Models\Produk::create([
            'kategori_id'=> 2,
            'kode_produk'=>'1009',
            'nama_produk'=>'Face Pallete DIOR',
             'harga_produk' => 487000,    // Harga beli/modal
            'harga_jual' => 488000,      // Harga jual sebelum diskon
            'diskon' => 0,             // Tidak ada diskon
            'harga' => 488000,           // Harga final (5000 - 0% = 5000)
        ]);

        \App\Models\Produk::create([
            'kategori_id'=> 2,
            'kode_produk'=>'1010',
            'nama_produk'=>'Liquid Lip Colour CHANEL',
             'harga_produk' => 880000,    // Harga beli/modal
            'harga_jual' => 881500,      // Harga jual sebelum diskon
            'diskon' => 0,             // Tidak ada diskon
            'harga' => 881500,           // Harga final (5000 - 0% = 5000)
        ]);

        \App\Models\Produk::create([
            'kategori_id'=> 3,
            'kode_produk'=>'1011',
            'nama_produk'=>'Kipas Angin',
             'harga_produk' => 629000,    // Harga beli/modal
            'harga_jual' => 630000,      // Harga jual sebelum diskon
            'diskon' => 0,             // Tidak ada diskon
            'harga' => 630000,           // Harga final (5000 - 0% = 5000)
        ]);

        \App\Models\Produk::create([
            'kategori_id'=> 3,
            'kode_produk'=>'1012',
            'nama_produk'=>'Setrika',
             'harga_produk' => 309000,    // Harga beli/modal
            'harga_jual' => 310000,      // Harga jual sebelum diskon
            'diskon' => 0,             // Tidak ada diskon
            'harga' => 310000,           // Harga final (5000 - 0% = 5000)
        ]);

        \App\Models\Produk::create([
            'kategori_id'=> 3,
            'kode_produk'=>'1013',
            'nama_produk'=>'Jam Alarm',
             'harga_produk' => 221000,    // Harga beli/modal
            'harga_jual' => 222000,      // Harga jual sebelum diskon
            'diskon' => 0,             // Tidak ada diskon
            'harga' => 222000,           // Harga final (5000 - 0% = 5000)
        ]);

        \App\Models\Produk::create([
            'kategori_id'=> 3,
            'kode_produk'=>'1014',
            'nama_produk'=>'CCTV',
             'harga_produk' => 249000,    // Harga beli/modal
            'harga_jual' => 250000,      // Harga jual sebelum diskon
            'diskon' => 0,             // Tidak ada diskon
            'harga' => 250000,           // Harga final (5000 - 0% = 5000)
        ]);

        \App\Models\Produk::create([
            'kategori_id'=> 3,
            'kode_produk'=>'1015',
            'nama_produk'=>'Bel Pintu',
             'harga_produk' => 120000,    // Harga beli/modal
            'harga_jual' => 121000,      // Harga jual sebelum diskon
            'diskon' => 0,             // Tidak ada diskon
            'harga' => 121000,           // Harga final (5000 - 0% = 5000)
        ]);

        \App\Models\Stok::create([
            'produk_id'=> 1,
            'nama_suplier'=>'Toko Haji Usman',
            'jumlah'=>250,
            'tanggal'=>date('Y-m-d', strtotime('-1 week'))
        ]);

        \App\Models\Stok::create([
            'produk_id'=> 2,
            'nama_suplier'=>'Toko Haji Usman',
            'jumlah'=> 100,
            'tanggal'=>date('Y-m-d', strtotime('-1 week'))
        ]);

        \App\Models\Stok::create([
            'produk_id'=> 3,
            'nama_suplier'=>'PT.C+',
            'jumlah'=> 100,
            'tanggal'=>date('Y-m-d', strtotime('-1 week'))
        ]);

        \App\Models\Stok::create([
            'produk_id'=> 4,
            'nama_suplier'=>'Balincer',
            'jumlah'=> 100,
            'tanggal'=>date('Y-m-d', strtotime('-1 week'))
        ]);

        \App\Models\Stok::create([
            'produk_id'=> 5,
            'nama_suplier'=>'Time',
            'jumlah'=> 100,
            'tanggal'=>date('Y-m-d', strtotime('-1 week'))
        ]);

        \App\Models\Stok::create([
            'produk_id'=> 6,
            'nama_suplier'=>'DIOR',
            'jumlah'=> 100,
            'tanggal'=>date('Y-m-d', strtotime('-1 week'))
        ]);

         \App\Models\Stok::create([
            'produk_id'=> 7,
            'nama_suplier'=>'DIOR',
            'jumlah'=> 100,
            'tanggal'=>date('Y-m-d', strtotime('-1 week'))
        ]);

         \App\Models\Stok::create([
            'produk_id'=> 8,
            'nama_suplier'=>'DIOR',
            'jumlah'=> 100,
            'tanggal'=>date('Y-m-d', strtotime('-1 week'))
        ]);

         \App\Models\Stok::create([
            'produk_id'=> 9,
            'nama_suplier'=>'DIOR',
            'jumlah'=> 100,
            'tanggal'=>date('Y-m-d', strtotime('-1 week'))
        ]);

         \App\Models\Stok::create([
            'produk_id'=> 10,
            'nama_suplier'=>'CHANEL',
            'jumlah'=> 100,
            'tanggal'=>date('Y-m-d', strtotime('-1 week'))
        ]);

         \App\Models\Stok::create([
            'produk_id'=> 11,
            'nama_suplier'=>'Cosmos',
            'jumlah'=> 100,
            'tanggal'=>date('Y-m-d', strtotime('-1 week'))
        ]);

         \App\Models\Stok::create([
            'produk_id'=> 12,
            'nama_suplier'=>'Philips',
            'jumlah'=> 100,
            'tanggal'=>date('Y-m-d', strtotime('-1 week'))
        ]);

         \App\Models\Stok::create([
            'produk_id'=> 13,
            'nama_suplier'=>'Weker',
            'jumlah'=> 100,
            'tanggal'=>date('Y-m-d', strtotime('-1 week'))
        ]);

         \App\Models\Stok::create([
            'produk_id'=> 14,
            'nama_suplier'=>'ADVAN',
            'jumlah'=> 100,
            'tanggal'=>date('Y-m-d', strtotime('-1 week'))
        ]);

         \App\Models\Stok::create([
            'produk_id'=> 15,
            'nama_suplier'=>'Krisbow',
            'jumlah'=> 100,
            'tanggal'=>date('Y-m-d', strtotime('-1 week'))
        ]);

        \App\Models\Produk::where('id', 1)->update([
            'stok'=>250,
        ]);

        \App\Models\Produk::where('id', 2)->update([
            'stok'=>100,
        ]);

        \App\Models\Produk::where('id', 3)->update([
            'stok'=>100,
        ]);

        \App\Models\Produk::where('id', 4)->update([
            'stok'=>100,
        ]);

        \App\Models\Produk::where('id', 5)->update([
            'stok'=>100,
        ]);

        \App\Models\Produk::where('id', 6)->update([
            'stok'=>100,
        ]);

        \App\Models\Produk::where('id', 7)->update([
            'stok'=>100,
        ]);

        \App\Models\Produk::where('id', 8)->update([
            'stok'=>100,
        ]);

        \App\Models\Produk::where('id', 9)->update([
            'stok'=>100,
        ]);

        \App\Models\Produk::where('id', 10)->update([
            'stok'=>100,
        ]);

        \App\Models\Produk::where('id', 11)->update([
            'stok'=>100,
        ]);

        \App\Models\Produk::where('id', 12)->update([
            'stok'=>100,
        ]);

        \App\Models\Produk::where('id', 13)->update([
            'stok'=>100,
        ]);

        \App\Models\Produk::where('id', 14)->update([
            'stok'=>100,
        ]);

        \App\Models\Produk::where('id', 15)->update([
            'stok'=>100,
        ]);

        
    }
}
