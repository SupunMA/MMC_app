<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\branches;
use App\Models\User;
use App\Models\Land;
use App\Models\Loan;

class admin_TransactionCtr extends Controller
{
   
 //Authenticate all Admin routes
    public function __construct()
    {
        $this->middleware('checkAdmin');
       // $this->task = new branches;
    }


//Transactions
    public function addTransaction()
    {
        return view('Users.Admin.Transactions.addTransaction');
    }

    public function allTransaction()
    {
        return view('Users.Admin.Transactions.allTransaction');
    }
}
