<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        return view('superadmin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('superadmin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rt' => 'required|string|max:3',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'rt' => $request->rt,
        ]);

        return redirect()->route('superadmin.admins.index')->with('success', 'Admin berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'rt' => "required|string|max:10|unique:users,rt,{$id}",
        ]);

        $admin->name = $request->name;
        $admin->rt = $request->rt;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('superadmin.admins.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $admin = User::findOrFail($id);

        // Optional: Prevent deleting yourself as superadmin
        if (auth()->id() === $id) {
            return redirect()->route('superadmin.admins.index')->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $admin->delete();

        return redirect()->route('superadmin.admins.index')->with('success', 'Admin berhasil dihapus.');
    }

    public function impersonate($id)
    {
        $admin = User::findOrFail($id);

        // Pastikan role adalah admin
        if ($admin->role !== 'admin') {
            return redirect()->route('superadmin.admins.index')->withErrors(['error' => 'Anda hanya bisa login sebagai akun admin.']);
        }

        // Simpan user ID superadmin untuk kembali
        session()->put('impersonate', auth()->id());

        // Login sebagai admin
        auth()->login($admin);

        return redirect('/admin')->with('success', 'Anda sekarang login sebagai ' . $admin->name);
    }

}
