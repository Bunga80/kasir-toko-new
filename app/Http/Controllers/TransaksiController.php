<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetilPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\User;
use Jackiedo\Cart\Facades\Cart;
use Illuminate\Support\Facades\DB;


class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $penjualans = Penjualan::join('users', 'users.id', 'penjualans.user_id')
            ->leftJoin('pelanggans', 'pelanggans.id', 'penjualans.pelanggan_id')
            ->select('penjualans.*', 'users.nama as nama_kasir', DB::raw('COALESCE(pelanggans.nama, "Pelanggan") as nama_pelanggan'))
            ->orderBy('id', 'desc')
            ->when($search, function ($q, $search) {
                return $q->where('nomor_transaksi', 'like', "%{$search}%");
            })
            ->paginate();

        if ($search) $penjualans->appends(['search' => $search]);
        return view('transaksi.index', [
            'penjualans' => $penjualans
        ]);
    }
    
    public function create(Request $request)
    {
        return view('transaksi.create', [
            'nama_kasir' => $request->user()->nama,
            'tanggal' => date('d F Y')
        ]);
    }

    public function getProduk($id)
{
    $produk = Produk::findOrFail($id);

    return response()->json([
        'id' => $produk->id,
        'nama' => $produk->nama_produk,
        'harga' => $produk->harga, // Gunakan harga final (setelah diskon)
        'harga_jual' => $produk->harga_jual, // Harga jual sebelum diskon
        'diskon' => $produk->diskon,
        'stok' => $produk->stok,
    ]);
}

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => ['nullable', 'exists:pelanggans,id'],
            'cash' => ['required', 'numeric', 'gte:total_bayar']
        ], [
            'cash.gte' => 'Uang tunai harus lebih besar atau sama dengan total bayar.'
        ], [
            'pelanggan_id' => 'pelanggan'
        ]);

        $user = $request->user();
        $lastPenjualan = Penjualan::orderBy('id', 'desc')->first();

        $cart = Cart::name($user->id);
        $cartDetails = $cart->getDetails();

        $total = $cartDetails->get('total');
        $kembalian = $request->cash - $total;

        $no = $lastPenjualan ? $lastPenjualan->id + 1 : 1;
        $no = sprintf("%04d", $no);

        // Hitung subtotal manual
    $allItems = $cartDetails->get('items');
    $subtotal = 0;
    foreach ($allItems as $item) {
        $subtotal += $item->price * $item->quantity;
    }

    // Validasi cash
    if ($request->cash < $total) {
        return redirect()->back()
            ->withErrors(['cash' => 'Cash kurang dari total yang harus dibayar.'])
            ->withInput();
    }

         $penjualan = Penjualan::create([
            'user_id' => $user->id,
            'pelanggan_id' => $cart->getExtraInfo('pelanggan')['id'] ?? null,
            'nomor_transaksi' => date('Ymd') . $no,
            'tanggal' => date('Y-m-d H:i:s'),
            'total' => $total,
            'tunai' => $request->cash,
            'kembalian' => $kembalian,
            'pajak' => $cartDetails->get('tax_amount'),
            'subtotal' => $cartDetails->get('subtotal')
        ]);

        $allItems = $cartDetails->get('items');

        $warnings = [];

        foreach ($allItems as $key => $value) {
            $item = $allItems->get($key);
            $produk = Produk::find($item->id);


            DetilPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'produk_id' => $item->id,
                'harga_produk' => $item->price, // Gunakan harga final (setelah diskon)
            'jumlah' => $item->quantity,
            'subtotal' => $item->price * $item->quantity
            ]);
            // Kurangi stok produk
    $produk = Produk::find($item->id);
    if ($produk) {
        $produk->stok -= $item->quantity;

        // Jika stok jadi minus → simpan pesan warning
        if ($produk->stok < 0) {
            $warnings[] = "⚠️ Stok produk {$produk->nama_produk} habis! Stok sekarang: {$produk->stok}";
        }

        $produk->save();
    }
}

// Hapus cart
$cart->destroy();

// Redirect ke halaman invoice + kirim warning (jika ada)
return redirect()
    ->route('transaksi.show', ['transaksi' => $penjualan->id])
    ->with('warning_stok', $warnings);

    }

    public function show(Request $request, Penjualan $transaksi)
    {
        $pelanggan = Pelanggan::find($transaksi->pelanggan_id);
        $user = User::find($transaksi->user_id);
        $detilPenjualan = DetilPenjualan::join('produks', 'produks.id', 'detil_penjualans.produk_id')
    ->select(
        'detil_penjualans.*',
        'nama_produk',
        'produks.diskon'
    )
    ->where('penjualan_id', $transaksi->id)->get();

    return view('transaksi.invoice', [
        'penjualan' => $transaksi,
        'pelanggan' => $pelanggan,
        'user' => $user,
        'detilPenjualan' => $detilPenjualan
    ]);
}

public function destroy(Request $request, Penjualan $transaksi)
    {
        // Hanya admin yang boleh batalkan
    if ($request->user()->role !== 'admin') {
        abort(403, 'Anda tidak punya izin untuk membatalkan transaksi.');
    }

        $transaksi->update([
            'status' => 'batal'
        ]);

        $detail = DetilPenjualan::where('penjualan_id', $transaksi->id)->get();

        foreach ($detail as $item) {
            $produk = Produk::find($item->produk_id);
            if ($produk) {
                $produk->stok += $item->jumlah;
                $produk->save();
            }
        }

        return back()->with('destroy', 'success');
    }

public function produk(Request $request)
{
    $search = $request->search;
    $produks = Produk::select('id', 'kode_produk', 'nama_produk')
        ->when($search, function ($q, $search) {
            return $q->where('nama_produk', 'like', "%{$search}%");
        })
        ->orderBy('nama_produk')
        ->take(15)
        ->get();

    return response()->json($produks);
}

public function pelanggan(Request $request)
{
    $search = $request->search;
    $pelanggans = Pelanggan::select('id', 'nama')
        ->when($search, function ($q, $search) {
            return $q->where('nama', 'like', "%{$search}%");
        })
        ->orderBy('nama')
        ->take(15)
        ->get();

    return response()->json($pelanggans);
}

public function addPelanggan(Request $request)
{
    $request->validate([
    'id' => ['nullable', 'exists:pelanggans,id'],
]);

$cart = Cart::name($request->user()->id);

if ($request->id) {
    $pelanggan = Pelanggan::find($request->id);
    $cart->setExtraInfo([
        'pelanggan' => [
            'id' => $pelanggan->id,
            'nama' => $pelanggan->nama,
        ]
    ]);
} else {
    // pelanggan umum
    $cart->setExtraInfo([
        'pelanggan' => [
            'id' => null,
            'nama' => 'Pelanggan',
        ]
    ]);
}

    return response()->json(['message' => 'Berhasil.']);
}

public function cetak(Penjualan $transaksi)
    {
        $pelanggan = Pelanggan::find($transaksi->pelanggan_id);
        $user = User::find($transaksi->user_id);
        $detilPenjualan = DetilPenjualan::join('produks', 'produks.id', 'detil_penjualans.produk_id')
            ->select(
                'detil_penjualans.*',
                'nama_produk',
                'produks.diskon'
            )
            ->where('penjualan_id', $transaksi->id)
            ->get();

        return view('transaksi.cetak', [
            'penjualan' => $transaksi,
            'pelanggan' => $pelanggan ? $pelanggan->nama : 'Pelanggan',
            'user' => $user,
            'detilPenjualan' => $detilPenjualan
        ]);
    }

}
