@extends('layouts.laporan', ['title' => 'Laporan Bulanan'])

@section('content')
<h1 class="text-center mb-4">📊 Laporan Bulanan</h1>
<p class="text-center">
    Bulan : <b>{{ $bulan }} {{ request()->tahun }}</b>
</p>

{{-- Ringkasan Transaksi --}}
<table class="table table-bordered table-sm text-center">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Transaksi Berhasil</th>
            <th>Transaksi Batal</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($penjualan as $i => $row)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $row->tgl }}</td>
            <td class="table-success">{{ $row->transaksi_selesai }}</td>
            <td class="table-danger">{{ $row->transaksi_batal }}</td>
            <td>{{ number_format($row->total_selesai, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr>
            <th colspan="2">Jumlah Total</th>
            <th class="table-success">{{ $totalBerhasilCount }}</th>
            <th class="table-danger">{{ $totalBatalCount }}</th>
            <th>{{ number_format($totalBerhasilSum, 0, ',', '.') }}</th>
        </tr>
    </tbody>
</table>

{{-- Top Produk --}}
<h4 class="mt-4">🏆 Top Produk Terlaris Bulan {{ $bulan }}</h4>
@if($topProduk->count() > 0)
<table class="table table-striped table-bordered">
    <thead class="table-primary">
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Total Terjual</th>
        </tr>
    </thead>
    <tbody>
        @foreach($topProduk as $index => $produk)
        <tr>
            <td>
                <span class="badge bg-dark">{{ $index+1 }}</span>
            </td>
            <td>{{ $produk->nama_produk }}</td>
            <td><span class="badge bg-success">{{ $produk->total_terjual }}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
    <p><i>Tidak ada produk terjual bulan ini.</i></p>
@endif

{{-- Kesimpulan --}}
<div class="mt-4 p-3 border rounded bg-light">
    <h4>📌 Kesimpulan</h4>
    <ul>
        <li><b>Transaksi Berhasil:</b> {{ $totalBerhasilCount }} transaksi (Total Rp {{ number_format($totalBerhasilSum, 0, ',', '.') }})</li>
        <li><b>Transaksi Batal:</b> {{ $totalBatalCount }} transaksi (Total Rp {{ number_format($totalBatalSum, 0, ',', '.') }})</li>
    </ul>
</div>
@endsection
