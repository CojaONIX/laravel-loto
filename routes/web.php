<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GameController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'home')->name('home.view');
Route::view('/welcome', 'welcome')->name('welcome.view');

Route::middleware('auth')->group(function () {

    Route::view('/admin', 'admin')->name('admin.view');

    Route::controller(GameController::class)->group(function () {
        Route::get('/game', 'index')->name('game.view');
        Route::post('/uplata-tiketa', 'addTicket')->name('game.ticket.add');
    });

    Route::controller(TransactionsController::class)->group(function () {
        Route::get('/transactions', 'index')->name('transactions.view');
        Route::post('/uplata-kredita', 'addCredit')->name('transactions.credit.add');
        Route::post('/isplata-sa-kredita', 'withdrawCredit')->name('transactions.credit.withdraw');
    });

    Route::controller(AdminController::class)->group(function () {
        Route::post('/isplata-dobitka', 'winnings')->name('transactions.credit.winnings');
    });

    Route::controller(TestController::class)->group(function () {
        Route::get('/test', 'showTest')->name('test.view');
        Route::post('/test', 'ajaxGetTestData');
    });
});





Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
