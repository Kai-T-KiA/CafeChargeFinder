<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FinderController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['middleware' => ['auth']], function() {
    Route::post('/save-location', [FinderController::class, 'save_location'])->name('save_location');
    Route::post('/posts', [FinderController::class, 'store'])->name('store');
    Route::get('/finder/home', [FinderController::class, 'home'])->name('home');
    Route::get('/finder/result', [FinderController::class, 'result'])->name('result');
    Route::get('/finder/regist', [FinderController::class, 'regist'])->name('regist');
    Route::get('/places/{place}', [FinderController::class, 'detail'])->name('detail');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
