<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CreditController;
use App\Http\Controllers\TestController;

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

Route::middleware('auth')->group(function () {

    Route::view('/uplata-kredita', 'uplataKredita')->name('kredit.uplata.view');
    Route::view('/isplata-sa-kredita', 'ispaltaSaKredita')->name('kredit.isplata.view');
    Route::view('/isplata-dobitka', 'isplataDobitka')->name('tiket.dobitak.view');

    Route::controller(CreditController::class)->group(function () {
        Route::get('/', 'homeKredit')->name('kredit.home');
        Route::post('/uplata-kredita', 'uplataKredita')->name('kredit.uplata');
        Route::post('/isplata-sa-kredita', 'isplataSaKredita')->name('kredit.isplata');
        Route::post('/uplata-tiketa', 'uplataTiketa')->name('tiket.uplata');
        Route::post('/isplata-dobitka', 'isplataDobitka')->name('tiket.dobitak');
    });
});

Route::get('/test', [TestController::class, 'showTest'])->name('test.page');
Route::post('/test', [TestController::class, 'ajaxGetTestData']);

Route::get('/welcome', function () {
    return view('welcome');
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
