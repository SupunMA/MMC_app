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
        //dd($data);
        //get loan date from db
        $this->loanDate = Loan::where('loanID', $data->transLoanID)->value('loanDate');

        $data->validate([

            'paidDate' => ['required','date','after:' . $this->loanDate],
            'transPaidAmount' => ['required','max:99999999','numeric'],
            'transLoanID' => ['required']

        ]);

        if($data->reduceLoan == true ){


            // update loan value by reducing
            Loan::where('loanID', $data->transLoanID)
            ->decrement('loanAmount', $data->transPaidAmount);

            //get transaction last record AllPaid Value
            $latestAllPaid= Transaction::select('transAllPaid')
            ->where('transLoanID',$data->transLoanID)
            ->orderBy('transID', 'desc')->first();

            $latestAllPaid = $latestAllPaid ?? 0; // check there is value, if not = 0

            // Create a new instance of the Transaction model
            $storeToTransaction = new Transaction();

            // Set the values for each column based on your data
            $storeToTransaction->paidDate = $data->paidDate;
            $storeToTransaction->transDetails = $data->transDetails;
            $storeToTransaction->transPaidAmount = $data->transPaidAmount;
            $storeToTransaction->transAllPaid = $data->transPaidAmount + $latestAllPaid;
            $storeToTransaction->transPaidInterest = 0;
            $storeToTransaction->transPaidPenaltyFee = 0;
            $storeToTransaction->transRestInterest = 0;
            $storeToTransaction->transRestPenaltyFee = 0;
            $storeToTransaction->transReducedAmount = $data->transPaidAmount;
            $storeToTransaction->transExtraMoney = 0;
            $storeToTransaction->transLoanID = $data->transLoanID;
            $storeToTransaction->transStatus = 1;


            // Save the model to the database
            $storeToTransaction->save();

            return redirect()->back()->with('message','Added Transaction!');
        }

        //pass data to calcInterest method (public variable)
        $this->requestData = $data;

        //get transaction last record from db
        $getTransactionData = Transaction::where('transLoanID',$data->transLoanID)
        ->where('transStatus', 0)
        ->orderBy('transID', 'desc')->first();


        //check is there old transactions
        if($getTransactionData){




        }else{

            $startDate = Carbon::parse($this->loanDate);
            $endDate = Carbon::parse($data->paidDate);
            // echo "{$startDate} - {$endDate}<br><br>";
            $currentMonthPayDate =  $endDate->day($startDate->day)->toDateString();
            // echo "{$currentMonthPayDate}<br><br>";

            if ($currentMonthPayDate > $data->paidDate){

                // echo "sss";
                $givenDate = Carbon::parse($currentMonthPayDate);
                // Get the number of days in the previous month
                $numberOfDaysInPreviousMonth = $givenDate->subMonthNoOverflow()->daysInMonth;

                if ($numberOfDaysInPreviousMonth == 31){
                    // echo"tttt";
                    $this->calcInterest(-1);

                }
                elseif($numberOfDaysInPreviousMonth == 28){
                    // echo"oooooo";
                    $this->calcInterest(+2);

                }
                elseif($numberOfDaysInPreviousMonth == 29){
                    // echo"iiiiii";
                    $this->calcInterest(+1);

                }
            }
            else{
                // transaction date
                $transPayDate = Carbon::parse($data->paidDate);
                // Get the day from transaction date
                $transPayDay = $transPayDate->day;

                if($transPayDay == 31){
                    // echo"uuuuuu";
                    $this->calcInterest(-1);
                }else{
                    // echo"xxxx";
                    $this->calcInterest(0);
                }
            }
        }
        return redirect()->back()->with('message','Added Transaction!');
    }


    private function calcInterest($changingDayDiff){
        //get request data from main method
        $requestData = $this->requestData;

        // assign variables
        $transPaidAmount = $requestData->transPaidAmount;
        $transPaidLateFee = 0;
        $transRestLateFee = 0;
        $transPaidInterest = 0;
        $transRestInterest = 0;
        $transExtraMoney = 0;
        $transReducedAmount = 0;

        //Get loan data from db
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
        //on monthly loan pay date will not add monthly interest, after that date add monthly interest value
        if(($monthsGap <> 0 || $yearsGap <> 0) && $daysGap == 0){

        }else{
            $totalInterest = $totalInterest + $monthlyInterest;
        }


        //Total late fees calculation
        //month diff is more than or 1, calculate late fees
        $monthsDifference = $startDate->diffInMonths($endDate);
        if($monthsDifference >= 1){

            $totalLateFee = $monthlyLateFee * 12 * $yearsGap;
            $totalLateFee = $totalLateFee + $monthlyLateFee * ($monthsGap - 1);
            $totalLateFee = $totalLateFee + $dailyLateFee * $daysGap;

        }elseif($monthsDifference == 0){

            $totalLateFee = 0;

        }

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

            // update loan value by reducing
            Loan::where('loanID', $requestData->transLoanID)
            ->decrement('loanAmount', $transReducedAmount);

        }

        // Create a new instance of the Transaction model
        $storeToTransaction = new Transaction();

        // Set the values for each column based on your data
        $storeToTransaction->paidDate = $requestData->paidDate;
        $storeToTransaction->transDetails = $requestData->transDetails;
        $storeToTransaction->transPaidAmount = $transPaidAmount;
        $storeToTransaction->transAllPaid = $transPaidAmount;
        $storeToTransaction->transPaidInterest = $transPaidInterest;
        $storeToTransaction->transPaidPenaltyFee = $transPaidLateFee;
        $storeToTransaction->transRestInterest = $transRestInterest;
        $storeToTransaction->transRestPenaltyFee = $transRestLateFee;
        $storeToTransaction->transReducedAmount = $transReducedAmount;
        $storeToTransaction->transExtraMoney = $transExtraMoney;
        $storeToTransaction->transLoanID = $requestData->transLoanID;


        // Save the model to the database
        $storeToTransaction->save();



        // $showdays = $diff->d + $changingDayDiff;
        //  echo "date: $daysGap\n<br><br>";
        //  echo "date: $monthsGap\n<br><br>";
        //  echo "date: $changingDayDiff\n<br><br>";

        //  echo "date: $requestData->paidDate\n";
        //  echo "paid amount: $transPaidAmount\n <br><br>";

        //  echo "transPaidLateFee: $transPaidLateFee\n";
        //  echo "transRestLateFee: $transRestLateFee\n<br><br>";

        //  echo "transPaidInterest: $transPaidInterest\n";
        //  echo "transRestInterest: $transRestInterest\n<br><br>";

        //  echo "transExtraMoney: $transExtraMoney\n";
        //  echo "transReducedAmount: $transReducedAmount\n";

    }

}
