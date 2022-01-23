<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\Admin\admin_HomeCtr;
use App\Http\Controllers\Admin\admin_BranchCtr;
use App\Http\Controllers\Admin\admin_ClientCtr;
use App\Http\Controllers\Admin\admin_LoanCtr;
use App\Http\Controllers\Admin\admin_LandCtr;
use App\Http\Controllers\Admin\admin_TransactionCtr;

use App\Http\Controllers\Home\homePageController;


use App\Http\Controllers\User\userController;
use App\Http\Controllers\Manager\managerController;
use App\Http\Controllers\Checker\checkerController;

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


Route::get('/', [homePageController::class, 'index'])->name('welcome');

//Preventing go back
Route::middleware(['middleware'=>'lockBack'])->group(function(){
    Auth::routes();
});


//common
//Route::get('/home', [HomeController::class, 'index'])->name('home');


//admin
Route::group(['prefix'=>'Admin','middleware'=>['checkAdmin','auth','lockBack']],function(){
    Route::get('/', [admin_HomeCtr::class, 'checkAdmin'])->name('admin.home');

    Route::get('AddClient', [admin_ClientCtr::class, 'addClient'])->name('admin.addClient');
    Route::get('AllClient', [admin_ClientCtr::class, 'allClient'])->name('admin.allClient');
    Route::POST('addingClient', [RegisterController::class, 'addingClient'])->name('admin.addingClient');
    Route::get('client/delete/{userID}', [admin_ClientCtr::class, 'deleteClient'])->name('admin.deleteClient');
    Route::post('client/update', [admin_ClientCtr::class, 'updateClient'])->name('admin.updateClient');

    Route::get('AddLand', [admin_LandCtr::class, 'addLand'])->name('admin.addLand');
    Route::get('AllLand', [admin_LandCtr::class, 'allLand'])->name('admin.allLand');
    Route::POST('addingLand', [admin_LandCtr::class, 'addingLand'])->name('admin.addingLand');
    Route::get('land/delete/{landID}', [admin_LandCtr::class, 'deleteLand'])->name('admin.deleteLand');
    Route::post('land/update', [admin_LandCtr::class, 'updateLand'])->name('admin.updateLand');
    
    Route::get('AddLoan', [admin_LoanCtr::class, 'addLoan'])->name('admin.addLoan');
    Route::get('AllLoan', [admin_LoanCtr::class, 'allLoan'])->name('admin.allLoan');
    Route::POST('addingLoan', [admin_LoanCtr::class, 'addingLoan'])->name('admin.addingLoan');
    Route::get('loan/delete/{loanID}', [admin_LoanCtr::class, 'deleteLoan'])->name('admin.deleteLoan');
    Route::post('loan/update', [admin_LoanCtr::class, 'updateLoan'])->name('admin.updateLoan');

    Route::get('AddBranch', [admin_BranchCtr::class, 'addBranch'])->name('admin.addBranch');
    Route::get('AllBranch', [admin_BranchCtr::class, 'allBranch'])->name('admin.allBranch');
    Route::POST('addingBranch', [admin_BranchCtr::class, 'addingBranch'])->name('admin.addingBranch');
    Route::get('branch/delete/{branchID}', [admin_BranchCtr::class, 'deleteBranch'])->name('admin.deleteBranch');
    Route::post('branch/update', [admin_BranchCtr::class, 'updateBranch'])->name('admin.updateBranch');

    Route::get('AddTransaction', [admin_TransactionCtr::class, 'addTransaction'])->name('admin.addTransaction');
    Route::get('AllTransaction', [admin_TransactionCtr::class, 'allTransaction'])->name('admin.allTransaction');
    Route::POST('addingTransaction', [admin_TransactionCtr::class, 'addingTransaction'])->name('admin.addingTransaction');
    // Route::get('branch/delete/{branchID}', [admin_TransactionCtr::class, 'deleteBranch'])->name('admin.deleteBranch');
    // Route::post('branch/update', [admin_TransactionCtr::class, 'updateBranch'])->name('admin.updateBranch');
    Route::post('transaction/delete/{transID}', [admin_TransactionCtr::class, 'deleteTransaction'])->name('admin.deleteTransaction');

    Route::POST('AllTransaction/{loanID}', [admin_TransactionCtr::class, 'allTransactionOfLoan'])->name('admin.allTransactionOfLoan');

    

    
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
Route::get('/register', function() {
    return redirect('/login');
});

Route::post('/register', function() {
    return redirect('/login');
});



//Re-set PWD and Create new account
Route::get('/forgot-register', function() {
    return view('other.forgotAndRegister');
})->name('forgotPWD');