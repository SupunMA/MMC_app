<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\branches;
use App\Models\User;
use App\Models\Land;
use App\Models\Loan;
use App\Models\Transaction;
use Carbon\Carbon;
use DateTime;

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
        $transactionData = Land::join('users','users.id','=','lands.ownerID')
        ->join('loans','loans.loanLandID','=','lands.landID')
        //->join('transactions','transactions.transLoanID','=','loans.loanID')
        ->get(['users.*','loans.*','lands.landID']);
        return view('Users.Admin.Transactions.allTransaction',compact('transactionData'));
    }

    public function allTransactionOfLoan($loanID)
    {
        $transactionData = Land::join('users','users.id','=','lands.ownerID')
        ->join('loans','loans.loanLandID','=','lands.landID')
        ->join('transactions','transactions.transLoanID','=','loans.loanID')
        ->where('loans.loanID',$loanID)->get(['users.name','users.NIC','loans.loanID','loans.loanDate','transactions.*']);
        return view('Users.Admin.Transactions.viewTransaction',compact('transactionData'));

    }

    public function deleteTransaction($transID)
    {
        //dd($branchID);
        $delete = Transaction::find($transID);
        $delete->delete();
        //return redirect()->back()->with('message','successful');
        return redirect()->route('admin.allTransaction')->with('message','Deleted Transaction!');;
    }



    public function addingTransaction(Request $data)
    {
        $data->validate([

            'paidDate' => ['required','date'],
            'transPaidAmount' => ['required','max:99999999','numeric'],
            'transLoanID' => ['required']

        ]);

        //dd($data->extraMoney);

        //pass data to calcInterest method
        $this->requestData = $data;

        $getTransactionData = Transaction::where('transLoanID',$data->transLoanID)
        ->orderBy('transID', 'desc')->first();

        $this->loanDate = Loan::where('loanID', $data->transLoanID)->value('loanDate');


        // $getDate = new DateTime();
        // $newDate = $getDate->format('Y-m-d');



       // dd($getTransactionData,$loanData,$newDate);


        if($getTransactionData){
            dd("not first");

        }else{


            // echo " - - monthly interest is {$monthlyInterest}-- {$monthlyLateFee} -- {$dailyLateFee}";

            $startDate = Carbon::parse($this->loanDate);
            $endDate = Carbon::parse($data->paidDate);
 echo " - -start {$startDate}-- end {$endDate}";


            $currentMonthPayDate =  $endDate->day($startDate->day)->toDateString();


            if ($currentMonthPayDate > $data->paidDate){
                // Your original date
                $givenDate = Carbon::parse($currentMonthPayDate);

                // Get the number of days in the previous month
                $numberOfDaysInPreviousMonth = $givenDate->subMonthNoOverflow()->daysInMonth;

                if ($numberOfDaysInPreviousMonth == 31){
                    $numberOfDaysInPreviousMonth = $numberOfDaysInPreviousMonth-1;
                    echo "{$numberOfDaysInPreviousMonth}";
                    $this->calcInterest();
                }
                elseif($numberOfDaysInPreviousMonth == 28){
                    $numberOfDaysInPreviousMonth = $numberOfDaysInPreviousMonth+2;
                    echo "{$numberOfDaysInPreviousMonth}";
                }
                elseif($numberOfDaysInPreviousMonth == 29){
                    $numberOfDaysInPreviousMonth = $numberOfDaysInPreviousMonth+1;
                    echo "{$numberOfDaysInPreviousMonth}";
                }

            }

            // echo "/ {$diff->y}-{$diff->m}-{$diff->d}  -  -  - {$currentMonthPayDate}";


        }


    }

    private function calcInterest(){
        //get request data from main method
        $requestData = $this->requestData;

        $loanData = Loan::where('loanID', $requestData->transLoanID)
        ->get()->first();

        $loanValue = $loanData['loanAmount'];
        $interestRate = $loanData['loanRate'];
        $lateFeeRate = $loanData['penaltyRate'];
        $loanDate = $loanData['loanDate'];

        $monthlyInterest = $loanValue * $interestRate/100;
        $monthlyLateFee = $loanValue * $lateFeeRate/100;
        $dailyLateFee = $monthlyLateFee/30;

        $startDate = Carbon::parse($this->loanDate);
        $endDate = Carbon::parse($requestData->paidDate);
        // Calculate the difference between the two dates
        $diff = $startDate->diff($endDate);
        $daysGap = $diff->d;
        $monthsGap = $diff->m;
        $yearsGap = $diff->y;

        //Interest calculation
        

        // echo "Days Gap: $daysGap\n";
        // echo "mon Gap: $monthsGap\n";
        // echo "years Gap: $yearsGap\n";
        // echo "hoooooo {$loanValue}";
    }

}
