<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketTypeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [DashboardController::class, 'store'])->name('ticket');
    Route::get('/dashboard/sort', [DashboardController::class, 'indexSort']);
});


Route::middleware('auth')->group(function() {
    Route::get('/types', [TicketTypeController::class, 'index'])->name('types');
    Route::post('/types', [TicketTypeController::class, 'store'])->name('types.store');
    Route::patch('/types', [TicketTypeController::class, 'update'])->name('types.update');
    Route::delete('/types', [TicketTypeController::class, 'destroy'])->name('types.destroy');
});

Route::middleware('auth')->group(function() {
    Route::post('/ticket', [TicketController::class, 'edit'])->name('ticket.edit');
    Route::patch('/ticket', [TicketController::class, 'update'])->name('ticket.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/upload', [TicketController::class, 'temporaryUpload']);
    Route::delete('/upload/{folder}', [TicketController::class, 'temporaryDelete']);
});

require __DIR__.'/auth.php';
