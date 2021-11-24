<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class adminController extends Controller
{
   
 //Authenticate all Admin routes
    public function __construct()
    {
        $this->middleware('checkAdmin');
    }


//Dashboard
    public function checkAdmin()
    {
        return view('Users.Admin.home');
    }

//Client

    public function addClient()
    {
        return view('Users.Admin.Clients.addClient');
    }

    public function allClient()
    {
        return view('Users.Admin.Clients.allClients');
    }


//Land

    public function addLand()
    {
        return view('Users.Admin.Lands.addLand');
    }

    public function allLand()
    {
        return view('Users.Admin.Lands.allLands');
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
