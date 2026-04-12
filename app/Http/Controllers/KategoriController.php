<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::latest('created_date')->get();
        return view('kategoris.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:1,0',
        ]);

        Kategori::create([
            'category_name' => $request->category_name,
            'description'   => $request->description,
            'status'        => $request->status,
            'is_deleted'    => 0,
            'created_by'    => auth()->user()->name ?? 'system',
            'created_date'  => now(),
        ]);

        return redirect()->route('kategoris.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:1,0',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'category_name'   => $request->category_name,
            'description'     => $request->description,
            'status'          => $request->status,
            'last_updated_by' => auth()->user()->name ?? 'system',
            'last_updated_date' => now(),
        ]);

        return redirect()->route('kategoris.index')->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategoris.index')->with('success', 'Kategori berhasil dihapus.');
    }
}