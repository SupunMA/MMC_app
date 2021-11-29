<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\userController;
use App\Http\Controllers\managerController;
use App\Http\Controllers\checkerController;

use Illuminate\Support\Facades\Auth;

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

//Preventing go back
Route::middleware(['middleware'=>'lockBack'])->group(function(){
    Auth::routes();
});


//common
//Route::get('/home', [HomeController::class, 'index'])->name('home');

// //admin
// Route::prefix('/Account/Admin/')->group(function(){
//     Route::get('/', [adminController::class, 'checkAdmin'])->name('admin.home');
//     Route::get('AddClient', [adminController::class, 'addClient'])->name('admin.addClient');
//     Route::get('AllClient', [adminController::class, 'allClient'])->name('admin.allClient');
//     Route::get('AddLand', [adminController::class, 'addLand'])->name('admin.addLand');
//     Route::get('AllLand', [adminController::class, 'allLand'])->name('admin.allLand');
//     Route::get('AddLoan', [adminController::class, 'addLoan'])->name('admin.addLoan');
//     Route::get('AllLoan', [adminController::class, 'allLoan'])->name('admin.allLoan');
// });

//admin
Route::group(['prefix'=>'Admin','middleware'=>['checkAdmin','auth','lockBack']],function(){
    Route::get('/', [adminController::class, 'checkAdmin'])->name('admin.home');
    Route::get('AddClient', [adminController::class, 'addClient'])->name('admin.addClient');
    Route::get('AllClient', [adminController::class, 'allClient'])->name('admin.allClient');
    Route::get('AddLand', [adminController::class, 'addLand'])->name('admin.addLand');
    Route::get('AllLand', [adminController::class, 'allLand'])->name('admin.allLand');
    Route::get('AddLoan', [adminController::class, 'addLoan'])->name('admin.addLoan');
    Route::get('AllLoan', [adminController::class, 'allLoan'])->name('admin.allLoan');
});

//user
Route::group(['prefix'=>'Account/Client','middleware'=>['checkUser','auth','lockBack']],function(){
    Route::get('/', [userController::class, 'checkUser'])->name('user.home');
    
});


//Manager
Route::group(['prefix'=>'Account/Manager','middleware'=>['checkManager','auth','lockBack']],function(){
    Route::get('/', [managerController::class, 'checkManager'])->name('manager.home');
    
});



//Checker
Route::group(['prefix'=>'Account/Checker','middleware'=>['checkChecker','auth','lockBack']],function(){
    Route::get('/', [checkerController::class, 'checkChecker'])->name('checker.home');
    
});



//Disabled User Registration
// Route::get('/register', function() {
//     return redirect('/login');
// });

// Route::post('/register', function() {
//     return redirect('/login');
// });
//


//Re-set PWD and Create new account
Route::get('/forgot-register', function() {
    return view('other.forgotAndRegister');
})->name('forgotPWD');