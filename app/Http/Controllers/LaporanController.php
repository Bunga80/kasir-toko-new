<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;


class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.form');
    }

    public function harian(Request $request)
    {
        $penjualan = DB::table('penjualans')
        ->join('users', 'users.id', '=', 'penjualans.user_id')
        ->leftJoin('pelanggans', 'pelanggans.id', '=', 'penjualans.pelanggan_id')
        ->whereDate('penjualans.tanggal', $request->tanggal)
        ->select(
            'penjualans.nomor_transaksi',
            'penjualans.status',
            'penjualans.tanggal',
            'users.nama as nama_kasir',
            DB::raw("COALESCE(pelanggans.nama, 'Pelanggan') as nama_pelanggan"),
            'penjualans.total'
        )
        ->orderBy('penjualans.id')
        ->get();

        // Hitung transaksi berhasil & batal
    $jumlah_berhasil = $penjualan->where('status', 'selesai')->count();
    $total_berhasil  = $penjualan->where('status', 'selesai')->sum('total');

    $jumlah_batal = $penjualan->where('status', 'batal')->count();
    $total_batal  = $penjualan->where('status', 'batal')->sum('total');


        // Barang minus stok + total terjual
    $barangMinus = DB::table('produks')
        ->leftJoin('detil_penjualans', 'produks.id', '=', 'detil_penjualans.produk_id')
        ->leftJoin('penjualans', 'detil_penjualans.penjualan_id', '=', 'penjualans.id')
        ->whereDate('penjualans.tanggal', $request->tanggal) // filter harian
        ->groupBy('produks.id', 'produks.nama_produk', 'produks.stok')
        ->having('produks.stok', '<', 0)
        ->select(
            'produks.nama_produk',
            'produks.stok',
            DB::raw('SUM(detil_penjualans.jumlah) as total_terjual')
        )
        ->get();

        // Top produk terjual hari ini
$topProduk = DB::table('detil_penjualans')
    ->join('produks', 'produks.id', '=', 'detil_penjualans.produk_id')
    ->join('penjualans', 'penjualans.id', '=', 'detil_penjualans.penjualan_id')
    ->whereDate('penjualans.tanggal', $request->tanggal)
    ->where('penjualans.status', 'selesai') // hanya transaksi selesai
    ->select(
        'produks.nama_produk',
        DB::raw('SUM(detil_penjualans.jumlah) as total_terjual')
    )
    ->groupBy('produks.nama_produk')
    ->orderByDesc('total_terjual')
    ->limit(5) // ambil 5 produk terlaris
    ->get();


    return view('laporan.harian', [
        'penjualan' => $penjualan,
        'barangMinus' => $barangMinus,
        'jumlah_berhasil' => $jumlah_berhasil,
        'total_berhasil'  => $total_berhasil,
        'jumlah_batal'    => $jumlah_batal,
        'total_batal'     => $total_batal,
        'topProduk'       => $topProduk
    ]);
    }

    public function bulanan(Request $request)
{
    $penjualan = Penjualan::select(
            DB::raw("DATE_FORMAT(tanggal, '%d/%m/%Y') as tgl"),
            DB::raw("SUM(CASE WHEN status = 'selesai' THEN total ELSE 0 END) as total_selesai"),
            DB::raw("COUNT(CASE WHEN status = 'selesai' THEN id END) as transaksi_selesai"),
            DB::raw("COUNT(CASE WHEN status = 'batal' THEN id END) as transaksi_batal")
        )
        ->whereMonth('tanggal', $request->bulan)
        ->whereYear('tanggal', $request->tahun)
        ->groupBy('tgl')
        ->get();

    // Total transaksi berhasil
    $totalBerhasil = Penjualan::whereMonth('tanggal', $request->bulan)
        ->whereYear('tanggal', $request->tahun)
        ->where('status', 'selesai');

    $totalBerhasilCount = $totalBerhasil->count();
    $totalBerhasilSum   = $totalBerhasil->sum('total');

    // Total transaksi batal
    $totalBatal = Penjualan::whereMonth('tanggal', $request->bulan)
        ->whereYear('tanggal', $request->tahun)
        ->where('status', 'batal');

    $totalBatalCount = $totalBatal->count();
    $totalBatalSum   = $totalBatal->sum('total');

    // 🔥 Top Produk Terlaris Bulanan
    $topProduk = DB::table('detil_penjualans')
        ->join('produks', 'produks.id', '=', 'detil_penjualans.produk_id')
        ->join('penjualans', 'penjualans.id', '=', 'detil_penjualans.penjualan_id')
        ->whereMonth('penjualans.tanggal', $request->bulan)
        ->whereYear('penjualans.tanggal', $request->tahun)
        ->where('penjualans.status', 'selesai') // hanya transaksi selesai
        ->select(
            'produks.nama_produk',
            DB::raw('SUM(detil_penjualans.jumlah) as total_terjual')
        )
        ->groupBy('produks.id', 'produks.nama_produk')
        ->orderByDesc('total_terjual')
        ->limit(10) // ambil 10 besar
        ->get();

    $nama_bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $bulan = $nama_bulan[$request->bulan - 1] ?? null;

    return view('laporan.bulanan', [
        'penjualan' => $penjualan,
        'bulan'     => $bulan,
        'totalBerhasilCount' => $totalBerhasilCount,
        'totalBerhasilSum'   => $totalBerhasilSum,
        'totalBatalCount'    => $totalBatalCount,
        'totalBatalSum'      => $totalBatalSum,
        'topProduk'          => $topProduk,
    ]);
}

public function keuntungan(Request $request)
{
    $bulan = $request->bulan;
    $tahun = $request->tahun;

    // Ambil data penjualan + detail + produk
    $penjualan = Penjualan::with(['detilPenjualan.produk'])
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->where('status', '!=', 'batal')
        ->get();

    $totalPendapatan = 0;
    $totalModal = 0;
    $detail = [];

    foreach ($penjualan as $pj) {
        foreach ($pj->detilPenjualan as $detil) {
            $pendapatan = $detil->jumlah * $detil->harga_produk;   // harga jual
            $modal = $detil->jumlah * $detil->produk->harga_produk; // harga beli supplier
            $laba = $pendapatan - $modal;

            $totalPendapatan += $pendapatan;
            $totalModal += $modal;

            $detail[] = (object) [
                'tanggal' => $pj->tanggal,
                'produk' => $detil->produk->nama_produk ?? '-',
                'jumlah' => $detil->jumlah,
                'harga_jual' => $detil->harga_produk,
                'harga_modal' => $detil->produk->harga_produk,
                'pendapatan' => $pendapatan,
                'modal' => $modal,
                'laba' => $laba,
            ];
        }
    }

    $totalLaba = $totalPendapatan - $totalModal;

    $nama_bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei',
        'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    return view('laporan.keuntungan', [
        'bulan' => $bulan,
        'tahun' => $tahun,
        'bulanNama' => $nama_bulan[$bulan - 1] ?? '',
        'detail' => $detail,
        'totalPendapatan' => $totalPendapatan,
        'totalModal' => $totalModal,
        'totalLaba' => $totalLaba,
    ]);
}
}
