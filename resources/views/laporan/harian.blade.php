@extends('layouts.laporan',['title'=>'Laporan Harian'])


@section('content')

<h1 class="text-center mb-4">📅 Laporan Harian</h1>

<p class="text-center">Tanggal : <b>{{ date('d/m/Y', strtotime( request()->tanggal )) }}</b></p>

<table table class="table table-bordered table-sm text-center">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>No. Transaksi</th>
            <th>Nama Pelanggan</th>
            <th>Kasir</th>
            <th>Status</th>
            <th>Waktu</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach ($penjualan as $item)
            <tr @if($item->status == 'batal') style="background:#f8d7da" @endif>
                <td>{{ $no++ }}</td>
                <td>{{ $item->nomor_transaksi }}</td>
                <td>{{ $item->nama_pelanggan }}</td>
                <td>{{ $item->nama_kasir }}</td>
                <td>
                    @if($item->status == 'selesai')
                        <span class="badge bg-success">{{ ucwords($item->status) }}</span>
                    @elseif($item->status == 'batal')
                        <span class="badge bg-danger">{{ ucwords($item->status) }}</span>
                    @else
                        <span class="badge bg-warning text-dark">{{ ucwords($item->status) }}</span>
                    @endif
                </td>
                <td>{{ date('H:i:s', strtotime($item->tanggal)) }}</td>
                <td>
                    @if($item->status == 'batal')
                        <s>{{ number_format($item->total, 0, ',', '.') }}</s>
                    @else
                        {{ number_format($item->total, 0, ',', '.') }}
                    @endif
                </td>
            </tr>
        @endforeach

        {{-- Baris Jumlah Total --}}
        <tfoot>
            <tr>
         <th colspan="6" class="text-end">Jumlah Total</th>
         <th>
                {{ number_format($penjualan->where('status','!=','batal')->sum('total'), 0, ',', '.') }}
</th>
        </tr>
        </tfoot>
    </tbody>
</table>

<br>

{{-- Barang Minus --}}
@if($barangMinus->count() > 0)
    <div class="card mt-4">
        <div class="card-header bg-danger text-white">
            <strong>⚠ Barang Minus Stok</strong>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Stok Sekarang</th>
                        <th>Total Terjual (Hari Ini)</th>
                        <th>Selisih (Minus)</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangMinus as $i => $b)
                        <tr class="table-danger">
                            <td>{{ $i+1 }}</td>
                            <td>{{ $b->nama_produk }}</td>
                            <td>{{ $b->stok }}</td>
                            <td>{{ $b->total_terjual }}</td>
                            <td>{{ abs($b->stok) }}</td>
                            <td>
                                <span class="badge bg-danger">⚠ Segera Restok</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <p class="mb-1"><strong>Total Barang Minus:</strong> {{ $barangMinus->count() }} item</p>
            <p class="mb-1"><strong>Total Kekurangan:</strong> {{ $barangMinus->sum(fn($b) => abs($b->stok)) }} pcs</p>
            <p class="mb-0"><strong>Daftar Barang Minus:</strong>  
                @foreach($barangMinus as $b) 
                    <span class="badge bg-warning text-dark">
                        {{ $b->nama_produk }} ({{ abs($b->stok) }} pcs)
                    </span>
                @endforeach
            </p>
        </div>
    </div>
@endif

{{-- Kesimpulan --}}
<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <strong>📊 Kesimpulan</strong>
    </div>
    <div class="card-body">
        <p class="mb-1 text-success">
            <strong>✔ Transaksi Berhasil:</strong> 
            {{ $jumlah_berhasil }} transaksi, 
            Total: {{ number_format($total_berhasil,0,',','.') }}
        </p>
        <p class="mb-0 text-danger">
            <strong>✘ Transaksi Batal:</strong> 
            {{ $jumlah_batal }} transaksi, 
            Total: {{ number_format($total_batal,0,',','.') }}
        </p>
    </div>
</div>
<br>
<h4 class="text-center mb-4">TOP PRODUK TERJUAL HARI INI</h4>
@if($topProduk->count() > 0)
    <table class="table table-bordered table-sm">
        <thead class="table-success">
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Total Terjual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProduk as $i => $tp)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $tp->nama_produk }}</td>
                <td>{{ number_format($tp->total_terjual, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p><i>Tidak ada transaksi hari ini.</i></p>
@endif


@endsection
