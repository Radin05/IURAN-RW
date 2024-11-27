<?php

use App\Http\Controllers\SuperAdmin\AdminManagementController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\PembayaranController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Super Admin
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // Dashboard Super Admin
    Route::get('/', function () {
        return view('superadmin.dashboard');
    })->name('dashboard');

    Route::post('admins/impersonate/{id}', [AdminManagementController::class, 'impersonate'])->name('admins.impersonate');

    Route::resource('admins', AdminManagementController::class)->except(['show']);

});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::get('users/filter', [AdminUserController::class, 'filter'])->name('users.filter');

    Route::get('users/{user}/pembayaran/create', [PembayaranController::class, 'create'])->name('users.pembayaran.create');
    Route::post('users/{user}/pembayaran', [PembayaranController::class, 'store'])->name('users.pembayaran.store');

});


Route::middleware(['auth'])->get('/stop-impersonating', function () {
    if (session()->has('impersonate')) {
        $superadminId = session()->get('impersonate');
        session()->forget('impersonate');

        // Login kembali sebagai superadmin
        auth()->loginUsingId($superadminId);

        return redirect('/superadmin/admins')->with('success', 'Kembali ke akun Super Admin.');
    }

    return redirect('/')->withErrors(['error' => 'Anda tidak dalam mode impersonasi.']);
})->name('stop.impersonating');
// User
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user', function () {
        return view('user.dashboard');
    })->name('home.user');
});

Auth::routes();

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
