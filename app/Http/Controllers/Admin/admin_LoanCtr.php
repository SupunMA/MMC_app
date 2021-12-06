<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class admin_LoanCtr extends Controller
{
   
   
 //Authenticate all Admin routes
    public function __construct()
    {
        $this->middleware('checkAdmin');
       // $this->task = new branches;
    }


//Loan

    public function addLoan()
    {
        return view('Users.Admin.Loans.addLoan');
    }

    public function allLoan()
    {
        return view('Users.Admin.Loans.allLoans');
    }

    
}
