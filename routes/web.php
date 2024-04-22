<?php

use App\Http\Controllers\AdminCounterController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('counters', CounterController::class)->only('index');
Route::get('counters/serving', [CounterController::class, 'serving'])->name('counters.serving');
Route::resource('tickets', TicketController::class)->except(['edit', 'update']);
Route::get('api/tickets/{ticket}', [TicketController::class, 'apiShow'])->name('api.ticket.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/customers', CustomerController::class)->except('show');
    Route::get('/import/customers', [CustomerController::class, 'import'])->name('customers.import');
    Route::post('/import/customers', [CustomerController::class, 'storeImport'])->name('customers.store-import');

    Route::resource('/admin/counters', AdminCounterController::class)->names('admin.counters');
    Route::get('/admin/queue/{counter}', [AdminCounterController::class, 'queue'])->name('admin.queue');
    Route::put('/admin/ticket', [AdminCounterController::class, 'actionTicket'])->name('admin.ticket.action');
});

require __DIR__.'/auth.php';
