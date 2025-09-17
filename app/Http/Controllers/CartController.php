<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Produk;
use Jackiedo\Cart\Facades\Cart;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::name($request->user()->id);

        $cart->applyTax([
            'id' => 1,
            'rate' => 5,
            'title' => 'Pajak PPN 5%'
        ]);

        return $cart->getDetails()->toJson();
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => ['required', 'exists:produks,kode_produk'],
            'quantity' => ['required', 'integer', 'min:1']
        ]);

        $produk = Produk::where('kode_produk', $request->kode_produk)->first();


        $cart = Cart::name($request->user()->id);

        

        $cart->addItem([
            'id' => $produk->id,
            'title' => $produk->nama_produk,
            'quantity' => $request->quantity,
            'price' => $produk->harga, // Harga final setelah diskon
            'options'=>[
                'kategori_id' => $produk->kategori_id,
                'diskon' => $produk->diskon,
                'harga_produk' => $produk->harga_produk, // Harga modal
                'harga_jual' => $produk->harga_jual, // Harga jual sebelum diskon
                'harga_final' => $produk->harga, // Harga final setelah diskon
            ]
        ]);

        return response()->json(['message' => 'Berhasil ditambahkan.']);
    }

    public function update(Request $request, $hash)
    {
        $request->validate([
            'qty' => ['required', 'in:-1,1']
        ]);

        $cart = Cart::name($request->user()->id);
        $item = $cart->getItem($hash);

        if (!$item) {
            return abort(404);
        }

        $cart->updateItem($item->getHash(), [
            'quantity' => $item->getQuantity() + $request->qty
        ]);

        return response()->json(['message' => 'Berhasil diupdate.']);
    }

    public function destroy(Request $request, $hash)
    {
        $cart = Cart::name($request->user()->id);
        $cart->removeItem($hash);

        return response()->json(['message' => 'Berhasil dihapus.']);
    }

    public function clear(Request $request)
    {
        $cart = Cart::name($request->user()->id);
        $cart->destroy();

        return back();
    }
}