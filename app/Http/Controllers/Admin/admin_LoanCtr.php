<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Land;
use App\Models\Loan;

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
        //->join('loans','loans.loanLandID','=','lands.landID')
        $ClientsWithLand = Land::join('users','users.id','=','lands.ownerID')
        ->where('users.role',0)
        ->get(['lands.landID', 'users.name','users.NIC']);

         //dd($LandsWithLoan);
        return view('Users.Admin.Loans.addLoan',compact('ClientsWithLand'));

    }

    public function allLoan()
    {
        return view('Users.Admin.Loans.allLoans');
    }

    public function addingLoan(Request $data)
    {
         $data->validate([
            'loanLandID' =>['required','unique:lands,landID'],
            'loanAmount' =>['required'],
            'loanRate' =>['required'],
            'penaltyRate' =>['required'],
            'loanDate' =>['required'],
            'dueDate' =>['required']
         ]);
        $user = Loan::create($data->all());
        return redirect()->back()->with('message','successful');
        //->route('your_url_where_you_want_to_redirect');
    }
    
}
