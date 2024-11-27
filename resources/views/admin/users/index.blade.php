@extends('layouts.navbar')

@section('content')

    <?php

    use Carbon\Carbon;

    ?>

    <style>
        table {
            width: auto;
            /* atau biarkan default */
        }

        td,
        th {
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="container">

        <h1>Kelola RT {{ Auth::user()->rt }}</h1>

        <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#createUserModal">
            Tambah User
        </button>

        {{-- Notifikasi --}}
        @foreach ($users as $user)
            @if (session('pembayaranBaru') && session('pembayaranBaru')['user_id'] == $user->id)
                <div class="alert alert-success">
                    <ul class="list-unstyled mb-0">
                        <li>
                            Pembayaran baru untuk <b>{{ $user->keluarga }}</b>
                        </li>
                        <li>
                            Dengan Jumlah: Rp.{{ number_format(session('pembayaranBaru')['amount'], 0, ',', '.') }}
                        </li>
                        <li>
                            pada tanggal
                            {{ \Carbon\Carbon::parse(session('pembayaranBaru')['tgl_pembayaran'])->locale('id')->format('d-m-Y') }}.
                        </li>
                    </ul>
                </div>
            @endif
        @endforeach

        @if (session('error'))
            <script>
                alert("{{ session('error') }}");
            </script>
        @endif
        {{-- END Notifikasi --}}

        {{-- Filter Tahun dan Bulan --}}
        <form action="{{ route('admin.users.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="year" class="form-label">Tahun</label>
                    <select name="year" id="year" class="form-select">
                        @for ($i = 2020; $i <= Carbon::now('Asia/Jakarta')->year; $i++)
                            <option {{ request('year', Carbon::now('Asia/Jakarta')->year) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="month" class="form-label">Bulan</label>
                    <select name="month" id="month" class="form-select">
                        @foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $key => $month)
                            <option value="{{ $key }}"
                                {{ request('month', Carbon::now('Asia/Jakarta')->month) == $key ? 'selected' : '' }}>
                                {{ $month }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary mt-4">Filter</button>
                </div>
            </div>
        </form>
        {{-- END Filter Tahun dan Bulan --}}


        <!-- Modal Tambah KELUARGA -->
        <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="createUserModalLabel">Tambah User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="keluarga" class="form-label">Nama Keluarga</label>
                                <input type="text" class="form-control @error('keluarga') is-invalid @enderror"
                                    id="keluarga" name="keluarga" value="{{ old('keluarga') }}">
                                @error('keluarga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="rt" class="form-label">RT</label>
                                <input type="text" class="form-control @error('rt') is-invalid @enderror" id="rt"
                                    name="rt" value="{{ old('rt', Auth::user()->rt) }}" readonly>
                                @error('rt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="jalan" class="form-label">Nama Jalan</label>
                                <input type="text" class="form-control @error('jalan') is-invalid @enderror"
                                    id="jalan" name="jalan" value="{{ old('jalan') }}">
                                @error('jalan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- END MODAL TAMBAH KELUARGA --}}

        {{-- TABLE KELUARGA --}}
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Keluarga</th>
                        <th>Email</th>
                        <th>RT</th>
                        <th>Jalan</th>
                        <th>Bayar</th>
                        <th>Tanggal & Waktu Bayar</th>
                        <th>Tahun & Bulan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($users as $user)
                        @php
                            // Ambil pembayaran yang sudah difilter dari eager loading
                            $filteredPayments = $user->pembayarans;
                        @endphp

                        <tr @if ($user->isLate) class="table-danger" @endif>
                            <td>{{ $loop->iteration }}</td>
                            <td title="{{ $user->keluarga }}">{{ $user->keluarga }}</td>
                            <td title="{{ $user->email }}">{{ $user->email }}</td>
                            <td>{{ $user->rt }}</td>
                            <td title="{{ $user->jalan }}">{{ $user->jalan }}</td>
                            <td>
                                @if ($filteredPayments->isNotEmpty())
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($filteredPayments as $pembayaran)
                                            <li>Rp.{{ number_format($pembayaran->amount, 0, ',', '.') }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    Belum ada pembayaran
                                @endif
                            </td>
                            <td>
                                @if ($filteredPayments->isNotEmpty())
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($filteredPayments as $pembayaran)
                                            <li>{{ $pembayaran->tgl_pembayaran->format('d M Y') }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($filteredPayments->isNotEmpty())
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($filteredPayments as $pembayaran)
                                            <li>
                                                {{ $pembayaran->year }} -
                                                {{ \Carbon\Carbon::createFromFormat('m', $pembayaran->month)->translatedFormat('F') }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($user->isLate)
                                    <span class="badge bg-danger">Belum Lunas</span>
                                @else
                                    <span class="badge bg-success">Lunas</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#paymentModal{{ $user->id }}">
                                    Tambah Pembayaran
                                </button>
                                <form
                                    action="{{ route('admin.users.destroy', $user->id) }}?year={{ request('year') }}&month={{ request('month') }}"
                                    method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada data ditemukan untuk tahun dan bulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- END TABLE KELUARGA --}}

        <!-- Modal Pembayaran -->
        @foreach ($users as $user)
            <div class="modal fade" id="paymentModal{{ $user->id }}" tabindex="-1"
                aria-labelledby="paymentModalLabel{{ $user->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.users.pembayaran.store', $user->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentModalLabel{{ $user->id }}">Tambah Pembayaran untuk
                                    {{ $user->keluarga }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Jumlah Pembayaran</label>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                        id="amount" name="amount" value="{{ old('amount') }}" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tgl_pembayaran" class="form-label">Tanggal Pembayaran</label>
                                    <input type="date"
                                        class="form-control @error('tgl_pembayaran') is-invalid @enderror"
                                        id="tgl_pembayaran" name="tgl_pembayaran" value="{{ old('tgl_pembayaran') }}"
                                        required>
                                    @error('tgl_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    {{-- Pilihan Tahun --}}
                                    <label for="year" class="form-label">Tahun</label>
                                    <input type="number" class="form-control" id="year" name="year"
                                        value="{{ request('year', now()->year) }}" readonly required>
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    {{-- Pilihan Bulan --}}
                                    <label for="month" class="form-label">Bulan</label>
                                    <input type="text" class="form-control" id="month" name="month"
                                        value="{{ request('month', now()->month) }}" readonly required>
                                    @error('month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        {{-- END MODAL PEMBAYARAN --}}


    </div>

    {{-- SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var createUserModal = new bootstrap.Modal(document.getElementById('createUserModal'));
                createUserModal.show();
            });
        </script>
    @endif

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const year = {{ request('year', now()->year) }};  // Mendapatkan tahun dari request
            const month = {{ request('month', now()->month) }};  // Mendapatkan bulan dari request
            const maxDate = new Date(year, month - 1, 1);  // Bulan dimulai dari 0, jadi bulan-1

            // Set tanggal maksimum ke akhir bulan yang dipilih
            const maxSelectableDate = new Date(maxDate.getFullYear(), maxDate.getMonth() + 1, 0);  // Set ke akhir bulan
            const maxDateString = maxSelectableDate.toISOString().split('T')[0];  // Format menjadi yyyy-mm-dd

            // Loop melalui setiap user untuk menyesuaikan tanggal pembayaran maksimal
            @foreach ($users as $user)
                const dateInput{{ $user->id }} = document.getElementById('tgl_pembayaran{{ $user->id }}');

                // Jika input tanggal ada, set atribut max
                if (dateInput{{ $user->id }}) {
                    dateInput{{ $user->id }}.setAttribute('max', maxDateString);  // Set tanggal maksimal yang dapat dipilih

                    // Tambahkan validasi saat tanggal dipilih
                    dateInput{{ $user->id }}.addEventListener('change', function() {
                        const selectedDate = new Date(this.value);
                        const selectedMonth = selectedDate.getMonth() + 1;  // Menyesuaikan bulan (0-indexed)

                        // Pastikan bulan yang dipilih tidak lebih besar dari bulan yang ditentukan
                        if (selectedMonth > month) {  // Jika bulan yang dipilih lebih besar dari bulan yang dipilih di filter
                            alert('Tanggal pembayaran tidak boleh berada di bulan depan atau lebih.');
                            this.value = '';  // Reset nilai input jika tidak valid
                        }
                    });
                }
            @endforeach
        });
    </script> --}}

    @if ($errors->has('amount') || $errors->has('tgl_pembayaran'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Loop melalui semua user untuk mencari modal yang tepat
                @foreach ($users as $user)
                    // Periksa jika error ada pada pembayaran untuk user ini
                    @if (old('user_id') == $user->id || $errors->has('amount') || $errors->has('tgl_pembayaran'))
                        // Pastikan hanya modal pembayaran yang ditampilkan
                        var paymentModal = new bootstrap.Modal(document.getElementById(
                            'paymentModal{{ $user->id }}'));
                        paymentModal.show(); // Menampilkan modal pembayaran yang sesuai
                    @endif
                @endforeach
            });
        </script>
    @endif


    {{-- END SCRIPT --}}
@endsection
