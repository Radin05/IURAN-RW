<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tahun dan bulan dari request, jika ada. Default ke tahun dan bulan sekarang (dalam zona waktu Indonesia).
        $year = $request->input('year', Carbon::now('Asia/Jakarta')->year); // Default ke tahun sekarang
        $month = $request->input('month', Carbon::now('Asia/Jakarta')->month); // Default ke bulan sekarang

        // Ambil semua pengguna dengan role "user" dan pre-load data pembayaran
        $users = User::where('role', 'user')
            ->with(['pembayarans' => function ($query) use ($year, $month) {
                $query->whereYear('tgl_pembayaran', $year)
                    ->whereMonth('tgl_pembayaran', $month);
            }])->get();

        $today = Carbon::now('Asia/Jakarta');

        $users = $users->map(function ($user) use ($today) {
            // Ambil pembayaran terakhir (jika ada)
            $lastPayment = $user->pembayarans->first();

            // Cek apakah sudah ada pembayaran dan apakah jumlahnya cukup
            $totalPayment = $user->pembayarans->sum('amount'); // Jumlah pembayaran

            // Cek apakah total pembayaran sudah mencapai Rp 100,000
            if ($totalPayment >= 100000) {
                $user->isLate = false;
            } else {
                $user->isLate = true;
            }

            if ($lastPayment) {
                $lastPaymentDate = Carbon::parse($lastPayment->tgl_pembayaran);
                // Cek apakah pembayaran terakhir dilakukan dalam 7 hari terakhir
                $user->highlight = $lastPaymentDate->diffInDays($today) <= 7;
            } else {
                $user->highlight = false;
            }

            return $user;
        });

        return view('admin.users.index', compact('users', 'year', 'month'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'keluarga' => 'required|string|max:255',
            'rt' => 'required|string|max:3',
            'jalan' => 'required|string|max:255',
        ]);

        User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'keluarga' => $request->keluarga,
            'rt' => Auth::user()->rt,
            'jalan' => $request->jalan,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $user->load('payments'); // Include payment history for the user
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'keluarga' => 'required|string|max:255',
            'jalan' => 'required|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'keluarga' => $request->keluarga,
            'jalan' => $request->jalan,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Informasi user berhasil diperbarui.');
    }

    public function destroy(User $user, Request $request)
    {
        // Hapus user
        $user->delete();

        // Ambil tahun dan bulan dari request (jika ada)
        $year = $request->query('year');
        $month = $request->query('month');

        // Redirect ke halaman yang sesuai dengan filter tahun dan bulan
        return redirect()->route('admin.users.index', compact('year', 'month'))
            ->with('success', 'User berhasil dihapus.');
    }

}
