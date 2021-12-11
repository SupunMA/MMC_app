<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\branches;
use App\Models\User;
use App\Models\Land;
use App\Models\Loan;
use App\Models\Transaction;

use save;

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
        $ClientsWithLoan = Land::join('users','users.id','=','lands.ownerID')
        ->join('loans','loans.loanLandID','=','lands.landID')
        ->where('users.role',0)
        ->get(['loans.loanID', 'users.name','users.NIC']);

         //dd($LandsWithLoan);
        return view('Users.Admin.Transactions.addTransaction',compact('ClientsWithLoan'));
    }

    public function allTransaction()
    {
        return view('Users.Admin.Transactions.allTransaction');
    }

    public function addingTransaction(Request $data)
    {
        $data->validate([

            'paidDate' => ['required'],
            'transPaidAmount' => ['required','max:99999999'],
            'transLoanID' => ['required','unique:loans,loanLandID'],

        ]);

        //dd($data);

        $getData = Transaction::where('transLoanID',$data->transLoanID)
        ->orderBy('transID', 'desc')->first();

        

        if ($getData == Null){
            
            $user = Transaction::create($data->all());
            return redirect()->back()->with('message','successful');
            //->route('your_url_where_you_want_to_redirect');

        }else{

            $newData = new Transaction();
            $newData->paidDate = $data->get('paidDate');
            $newData->transPaidAmount = $data->get('transPaidAmount');
            $newData->transLoanID = $data->get('transLoanID');
            $newData->transDetails = $data->get('transDetails');

            $newData->transAllPaid = ($getData->transAllPaid) + ($getData->transAllPaid);
            $newData->transReducedAmount = $getData->transReducedAmount;
            $newData->transPaidInterest = $getData->transPaidInterest;
            $newData->transPaidPenaltyFee = $getData->transPaidPenaltyFee;
            $newData->transRestInterest = $getData->transRestInterest;
            $newData->transRestPenaltyFee = $getData->transRestPenaltyFee;
            
            $newData->save();
            
            return redirect()->back()->with('message','successful');

            // $data->validate([
            //     'loanLandID' =>['required','unique:loans,loanLandID'],
            //     'loanAmount' =>['required'],
            //     'loanRate' =>['required'],
            //     'penaltyRate' =>['required'],
            //     'loanDate' =>['required'],
            //     'dueDate' =>['required']
            //  ]);

            // Transaction::where('transID', $getData->transID)
            // ->update([

            // 'transAllPaid' => ($getData->transAllPaid) +676.0,
            // 'transReducedAmount' => $getData->transReducedAmount,
            // 'transPaidInterest' => $getData->transPaidInterest,
            // 'transPaidPenaltyFee' => $getData->transPaidPenaltyFee,
            // 'transRestInterest' => $getData->transRestInterest,
            // 'transRestPenaltyFee' => $getData->transRestPenaltyFee
            
            // ]);
    
            

        }
        
    }
}
