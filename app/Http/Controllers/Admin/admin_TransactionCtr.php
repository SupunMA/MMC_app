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

        //pass data to calcInterest method
        $this->requestData = $data;

        $getTransactionData = Transaction::where('transLoanID',$data->transLoanID)
        ->orderBy('transID', 'desc')->first();

        $this->loanDate = Loan::where('loanID', $data->transLoanID)->value('loanDate');

        if($getTransactionData){
            dd("not first");

        }else{

            $startDate = Carbon::parse($this->loanDate);
            $endDate = Carbon::parse($data->paidDate);

            $currentMonthPayDate =  $endDate->day($startDate->day)->toDateString();

            if ($currentMonthPayDate > $data->paidDate){
                // Your original date
                $givenDate = Carbon::parse($currentMonthPayDate);

                // Get the number of days in the previous month
                $numberOfDaysInPreviousMonth = $givenDate->subMonthNoOverflow()->daysInMonth;

                if ($numberOfDaysInPreviousMonth == 31){

                    $this->calcInterest(-1);

                }
                elseif($numberOfDaysInPreviousMonth == 28){

                    $this->calcInterest(+2);

                }
                elseif($numberOfDaysInPreviousMonth == 29){

                    $this->calcInterest(+1);

                }

            }
            else{
                // Your date value
                $transPayDate = Carbon::parse($data->paidDate);
                // Get the number of days in transaction date month
                $daysInTransPayMonth = $date->daysInMonth;

                if($daysInTransPayMonth == 31){
                    $this->calcInterest(-1);
                }
            }

        }


    }

    private function calcInterest($changingDayDiff){
        //get request data from main method

        $transPaidLateFee = 0;
        $transRestLateFee = 0;
        $transPaidInterest = 0;
        $transRestInterest = 0;
        $transExtraMoney = 0;
        $transReducedAmount = 0;

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
        // Calculate the difference between loan date and transaction date
        $diff = $startDate->diff($endDate);
        $daysGap = $diff->d + $changingDayDiff; //according to addingTransaction method add or minus value
        $monthsGap = $diff->m;
        $yearsGap = $diff->y;

        //Total Interest calculation
        $totalInterest = $monthlyInterest * 12 * $yearsGap;
        $totalInterest = $totalInterest + $monthlyInterest * $monthsGap;
        $totalInterest = $totalInterest + $monthlyInterest;

        //Total late fees calculation
        $totalLateFee = $monthlyLateFee * 12 * $yearsGap;
        $totalLateFee = $totalLateFee + $monthlyLateFee * $monthsGap;
        $totalLateFee = $totalLateFee + $dailyLateFee * $daysGap;


        //Add late fees to DB $transPaidLateFee - $transRestLateFee
        if($requestData->transPaidAmount >= $totalLateFee){

            $transPaidLateFee = $totalLateFee;
            $transRestLateFee = 0;
            $requestData->transPaidAmount = $requestData->transPaidAmount - $totalLateFee;

        }
        else{

            $transPaidLateFee = $requestData->transPaidAmount;
            $transRestLateFee = $totalLateFee - $requestData->transPaidAmount;
            $requestData->transPaidAmount = 0;

        }

        //Add interest to DB
        if($requestData->transPaidAmount >= $totalInterest){
            $transPaidInterest = $totalInterest;
            $transRestInterest = 0;
            $requestData->transPaidAmount= $requestData->transPaidAmount - $totalInterest;
        }
        else{

            $transPaidInterest = $requestData->transPaidAmount;
            $transRestInterest = $totalInterest - $requestData->transPaidAmount;
            $requestData->transPaidAmount = 0;

        }

        //Add extra money or reduce from loan
        if($requestData->extraMoney == "keep" && $requestData->transPaidAmount > 0 ){
            $transExtraMoney = $requestData->transPaidAmount;
        }
        elseif($requestData->extraMoney == "reduce" && $requestData->transPaidAmount > 0 ) {
            $transReducedAmount = $requestData->transPaidAmount;
        }
         echo "transPaidLateFee: $transPaidLateFee\n";
         echo "transRestLateFee: $transRestLateFee\n";

         echo "transPaidInterest: $transPaidInterest\n";
         echo "transRestInterest: $transRestInterest\n";

         echo "transExtraMoney: $transExtraMoney\n";
         echo "transReducedAmount: $transReducedAmount\n";

    }

}
