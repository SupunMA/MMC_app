<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\adminController;

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
    return view('Home.welcome');
})->name('welcome');

Auth::routes();

//user
Route::get('/Account/User', [HomeController::class, 'index'])->name('user.home');

//admin
Route::prefix('/Account/Admin/')->group(function(){
    Route::get('/', [adminController::class, 'checkAdmin'])->name('admin.home');
    Route::get('AddClient', [adminController::class, 'addClient'])->name('admin.addClient');
    Route::get('AllClient', [adminController::class, 'allClient'])->name('admin.allClient');
    Route::get('AddLand', [adminController::class, 'addLand'])->name('admin.addLand');
    Route::get('AllLand', [adminController::class, 'allLand'])->name('admin.allLand');
    Route::get('AddLoan', [adminController::class, 'addLoan'])->name('admin.addLoan');
    Route::get('AllLoan', [adminController::class, 'allLoan'])->name('admin.allLoan');
});


//Disabled User Registration
Route::get('/register', function() {
    return redirect('/login');
});

Route::post('/register', function() {
    return redirect('/login');
});
//;