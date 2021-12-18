<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $LoansWithLand = Land::join('users','users.id','=','lands.ownerID')
        ->join('loans','loans.loanLandID','=','lands.landID')
        ->where('users.role',0)
        ->get([
        'loans.loanID',
        'users.name',
        'users.NIC',
        'lands.landID',
        'loans.loanAmount',
        'loans.loanRate',
        'loans.penaltyRate',
        'loans.loanDate',
        //'loans.dueDate',
        'loans.description']);
        //dd($ClientsWithLand);
        return view('Users.Admin.Loans.allLoans',compact('LoansWithLand'));
    }

    public function addingLoan(Request $data)
    {
         $data->validate([
            'loanLandID' =>['required','unique:loans,loanLandID'],
            'loanAmount' =>['required'],
            'loanRate' =>['required'],
            'penaltyRate' =>['required'],
            'loanDate' =>['required'],
            //'dueDate' =>['required']
         ]);
        $user = Loan::create($data->all());
        return redirect()->back()->with('message','successful');
        //->route('your_url_where_you_want_to_redirect');
    }
    
    public function deleteLoan($loanID)
    {
        //dd($branchID);
        $delete = Loan::find($loanID);
        $delete->delete();
        return redirect()->back()->with('message','successful');
    }

    public function updateLoan(Request $data)
    {

        //dd($data);
        $data->validate([
            //'loanLandID' =>['required','unique:loans,loanLandID'],
            'loanAmount' =>['required'],
            'loanRate' =>['required'],
            'penaltyRate' =>['required'],
            'loanDate' =>['required'],
            //'dueDate' =>['required']
         ]);
        Loan::where('loanID', $data->loanID)
        ->update(['loanRate' => $data->loanRate,
            'loanAmount' => $data->loanAmount,
            'penaltyRate' => $data->penaltyRate,
            'loanDate' => $data->loanDate,
            //'dueDate' => $data->dueDate,
            //'loanLandID'=> $data->loanLandID,
            'description' => $data->description]);

        return redirect()->back()->with('message','successful');
    }
}
