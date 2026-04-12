<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\Kategori;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategori')->get();
        $kategoris = Kategori::all();
        return view('produk.index', compact('produks', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('produk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name'    => 'required',
            'rental_price'    => 'required|numeric',
            'stock'           => 'required|integer',
            'condition'       => 'required',
            'category_id'     => 'required|exists:categories,id',
            'photo'           => 'nullable|image|max:2048',
            'created_date'    => 'nullable|date',
            'min_rental_days' => 'nullable|integer|min:1',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('products', 'public');
        }

        Produk::create([
            'product_name'    => $request->product_name,
            'description'     => $request->description,
            'rental_price'    => $request->rental_price,
            'stock'           => $request->stock,
            'condition'       => $request->condition,
            'category_id'     => $request->category_id,
            'photo'           => $photoPath,
            'created_date'    => $request->created_date,
            'min_rental_days' => $request->min_rental_days ?? 1,
            'status'          => 1,
            'is_deleted'      => 0,
            'created_by'      => auth()->user()->name ?? 'system',
        ]);

        return redirect('/produk');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategoris = Kategori::all();
        return view('produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $produk->update([
            'product_name'    => $request->product_name,
            'description'     => $request->description,
            'rental_price'    => $request->rental_price,
            'stock'           => $request->stock,
            'condition'       => $request->condition,
            'category_id'     => $request->category_id,
            'last_updated_by' => auth()->user()->name ?? 'system',
        ]);

        return redirect('/produk');
    }

    public function destroy($id)
    {
        Produk::destroy($id);
        return redirect('/produk');
    }
}