<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function create(User $user)
    {
        return view('admin.pembayaran.create', compact('user'));
    }

    public function store(Request $request, User $user)
    {
        // Menangkap tahun dan bulan yang dipilih
        $year = $request->year;
        $month = $request->month;

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'tgl_pembayaran' => 'required|date',
        ]);

        $pembayaran = new Pembayaran();
        $pembayaran->amount = $request->amount;
        $pembayaran->tgl_pembayaran = $request->tgl_pembayaran;
        $pembayaran->user_id = $user->id;
        $pembayaran->year = $request->year;
        $pembayaran->month = $request->month;
        $pembayaran->save();

        session()->flash('pembayaranBaru', [
            'user_id' => $user->id,
            'amount' => $pembayaran->amount,
            'tgl_pembayaran' => $pembayaran->tgl_pembayaran,
        ]);

        // Kirim data yang sudah difilter ke view
        return redirect()->route('admin.users.index',
            [
                'year' => $year,
                'month' => $month,
            ])->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    public function filter(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');

        $users = User::with(['pembayarans' => function ($query) use ($year, $month) {
            if ($year) {
                $query->whereYear('tgl_pembayaran', $year);
            }

            if ($month) {
                $query->whereMonth('tgl_pembayaran', $month);
            }
        }])->get();

        return view('admin.users.index', compact('users'));
    }

}
