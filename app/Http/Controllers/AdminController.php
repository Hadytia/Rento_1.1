<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::latest('created_date')->get();
        return view('admins.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:superadmin,admin',
            'status'   => 'required|in:1,0',
        ]);

        Admin::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => bcrypt($request->password),
            'role'        => $request->role,
            'status'      => $request->status,
            'is_deleted'  => 0,
            'created_by'  => auth()->user()->name ?? 'system',
            'created_date' => now(),
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admins,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role'     => 'required|in:superadmin,admin',
            'status'   => 'required|in:1,0',
        ]);

        $data = [
            'name'              => $request->name,
            'email'             => $request->email,
            'role'              => $request->role,
            'status'            => $request->status,
            'last_updated_by'   => auth()->user()->name ?? 'system',
            'last_updated_date' => now(),
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $admin->update($data);

        return redirect()->route('admins.index')->with('success', 'Admin berhasil diupdate.');
    }

    public function destroy($id)
    {
        Admin::findOrFail($id)->delete();
        return redirect()->route('admins.index')->with('success', 'Admin berhasil dihapus.');
    }
}