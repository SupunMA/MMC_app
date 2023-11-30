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
        ->where('transactions.transPaidAmount','!=',0)
        ->where('loans.loanID',$loanID)->get(['users.name','users.NIC','loans.loanID','loans.loanDate','transactions.*']);
        return view('Users.Admin.Transactions.viewTransaction',compact('transactionData'));

    }

    public function deleteTransaction($transID)
    {
        //dd($branchID);
        $delete = Transaction::find($transID);
        $delete->delete();
        // return redirect()->back()->with('message','successful');
        return redirect()->route('admin.allTransaction')->with('message','Deleted Transaction!');
    }



    public function addingTransaction(Request $data)
    {
        //dd($data);
        //get loan date from db
        $this->loanDate = Loan::where('loanID', $data->transLoanID)->value('loanDate');

        $getTransactionData = Transaction::where('transLoanID',$data->transLoanID)
        ->select('paidDate')
        ->where('transStatus', 0)
        ->orderBy('transID', 'desc')->first();

        if(!$getTransactionData){
            $getTransactionData = $this->loanDate;
        }

        $data->validate([

            'paidDate' => ['required','date','after:' . $this->loanDate, 'after:' . $getTransactionData],
            'transPaidAmount' => ['required','max:99999999','numeric'],
            'transLoanID' => ['required']

        ]);

        //Reduce Loan Amount Directly
        if($data->reduceLoan == true ){


            // update loan value by reducing
            Loan::where('loanID', $data->transLoanID)
            ->decrement('loanAmount', $data->transPaidAmount);

            //get transaction last record AllPaid Value
            $latestAllPaid= Transaction::where('transLoanID',$data->transLoanID)
            ->orderBy('transID', 'desc')->first();

            $latestAllPaid['transAllPaid'] = $latestAllPaid['transAllPaid'] ?? 0; // check there is value, if not = 0
            // dd($data->transPaidAmount + $latestAllPaid['transAllPaid']);
            // Create a new instance of the Transaction model
            $storeToTransaction = new Transaction();

            // Set the values for each column based on your data
            $storeToTransaction->paidDate = $data->paidDate;
            $storeToTransaction->transDetails = $data->transDetails;
            $storeToTransaction->transPaidAmount = $data->transPaidAmount;
            $storeToTransaction->transAllPaid = ($data->transPaidAmount + $latestAllPaid['transAllPaid']);
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

        $this->getTransactionData = $getTransactionData;
        //check is there old transactions
        if($getTransactionData){

            $newTransDate = Carbon::parse($data->paidDate);
            $newTransDate2 = Carbon::parse($data->paidDate);
            $oldTransDate = Carbon::parse($getTransactionData->paidDate);
            $loanDate = Carbon::parse($this->loanDate);
            if ($newTransDate->month == $oldTransDate->month && $newTransDate->year == $oldTransDate->year) {

                $loanDate = Carbon::parse($this->loanDate);
                $newTransDate = Carbon::parse($data->paidDate);
                // echo "{$startDate} - {$endDate}<br><br>";
                $payDateNewTransDate =  $newTransDate->day($loanDate->day)->toDateString();
                $lateFeeForSmallLoan = 0;
                if($payDateNewTransDate > $loanDate){
                    $newTransDate = Carbon::parse($data->paidDate);
                    $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                    $payDateOldTransDate =  $oldTransDate->day($loanDate->day)->toDateString();
                    // Calculate the $newTransDate, oldTransDate difference in days
                    $diffInDaysOldTransNewTransDates = $newTransDate->diffInDays($oldTransDate);
                    //  dd($diffInDaysOldTransNewTransDates,$newTransDate,$oldTransDate);
                    //Get loan data from db
                    $loanData = Loan::where('loanID', $data->transLoanID)
                    ->get()->first();

                    $loanValue = $loanData['loanAmount'];
                    $interestRate = $loanData['loanRate'];
                    $lateFeeRate = $loanData['penaltyRate'];

                    $monthlyInterest = $loanValue * $interestRate/100;



                    if($diffInDaysOldTransNewTransDates >= 30){



                        $loanDate = Carbon::parse($this->loanDate);
                        $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                        $payDateOldTransDate =  $oldTransDate->day($loanDate->day);
                        $oldTransDate = Carbon::parse($getTransactionData->paidDate);


                        if($payDateOldTransDate > $oldTransDate){

                            if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                $diffOldTransAndPayDateOldTransDates = $oldTransDate->diff($payDateOldTransDate);
                                $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                $getSmallLoan = $getSmallInterest * ($interestRate / 100);
                                $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                $lateFeeForSmallLoan = $diffOldTransAndPayDateOldTransDates->d * $getDailyLateFeeForSmallLoan;
                            }
                        }elseif($payDateOldTransDate == $oldTransDate){
                            //
                        }elseif($payDateOldTransDate < $oldTransDate){
                            echo "sdf";
                            if(($getTransactionData->transRestInterest ) > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                $nextPayDateOldTransDate =  $oldTransDate->day($loanDate->day)->addMonth()->toDateString();
                                $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                                echo "{$nextPayDateOldTransDate}- { $oldTransDate}<br>";
                                $diffOldTransAndNextPayDateOldTransDates = $oldTransDate->diffInDays($nextPayDateOldTransDate);
                                echo "{$diffOldTransAndNextPayDateOldTransDates}<br>";


                                if($oldTransDate->daysInMonth == 31){
                                    $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates - 1;
                                }elseif($oldTransDate->daysInMonth == 28){
                                    $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates + 2;
                                }elseif($oldTransDate->daysInMonth == 29){
                                    $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates + 1;
                                }

                                 //dd($diffOldTransAndNextPayDateOldTransDates);
                                $getSmallInterest = ($getTransactionData->transRestInterest) - $monthlyInterest;
                                echo "{$getSmallInterest}<br>";

                                $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                echo "{$getSmallLoan}<br>";

                                $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                $lateFeeForSmallLoan = $diffOldTransAndNextPayDateOldTransDates * $getDailyLateFeeForSmallLoan;


                            }
                        }
                    }else{
                        $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                        if ($newTransDate->month == $oldTransDate->month){

                            if($oldTransDate < $payDateOldTransDate && $payDateOldTransDate < $newTransDate){

                                if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                    $diffInDaysOldTransAndPayDateOldTransDates = $oldTransDate->diffInDays($payDateOldTransDate);

                                    $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                    $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                    $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                    $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                    $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateOldTransDates * $getDailyLateFeeForSmallLoan;
                                }

                            }elseif($oldTransDate < $newTransDate && $newTransDate <= $payDateOldTransDate){

                                if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                    $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);

                                    $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                    $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                    $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                    $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                    $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                                }

                            }elseif($payDateOldTransDate <= $oldTransDate && $oldTransDate < $newTransDate){


                                if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                    $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);
                                    // dd("htu",$diffInDaysOldTransAndNewTransDates,$oldTransDate,$newTransDate);
                                    if($newTransDate->day == 31){
                                        $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates - 1;
                                    }

                                    $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                    $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                    $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                    $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                    $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                                }


                            }

                        }else{
                            if($oldTransDate < $payDateOldTransDate){
                                if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                    $diffInDaysOldTransAndPayDateOldTransDates = $oldTransDate->diffInDays($payDateOldTransDate);

                                    $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                    $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                    $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                    $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                    $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateOldTransDates * $getDailyLateFeeForSmallLoan;
                                }
                            }else{
                                if($newTransDate <= $payDateNewTransDate){
                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);


                                        if($oldTransDate->daysInMonth == 31){
                                            $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates - 1;
                                        }elseif($oldTransDate->daysInMonth == 28){
                                            $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates + 2;
                                        }elseif($oldTransDate->daysInMonth == 29){
                                            $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates + 1;
                                        }

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                                    }
                                }elseif($payDateNewTransDate < $newTransDate){
                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndPayDateNewTransDates = $oldTransDate->diffInDays($payDateNewTransDate);

                                        if($oldTransDate->daysInMonth == 31){
                                            $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates - 1;
                                        }elseif($oldTransDate->daysInMonth == 28){
                                            $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates + 2;
                                        }elseif($oldTransDate->daysInMonth == 29){
                                            $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates + 1;
                                        }

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateNewTransDates * $getDailyLateFeeForSmallLoan;
                                    }
                                }
                            }
                        }
                    }
                }


                // assign variables
                $transPaidAmount = $data->transPaidAmount + $getTransactionData->transExtraMoney;
                $transPaidLateFee = $data->transPaidPenaltyFee;
                $transRestLateFee = $lateFeeForSmallLoan + $getTransactionData->transRestPenaltyFee;
                $transPaidInterest = $data->transPaidInterest;
                $transRestInterest = $getTransactionData->transRestInterest;
                $transExtraMoney = 0;
                $transReducedAmount = 0;
                $totalInterest = 0;
                $this->lateFeeForSmallLoan = 0;


                // dd($lateFeeForSmallLoan);

                //Get loan data from db
                $loanData = Loan::where('loanID', $data->transLoanID)
                ->get()->first();

                $loanValue = $loanData['loanAmount'];
                $interestRate = $loanData['loanRate'];
                $lateFeeRate = $loanData['penaltyRate'];
                $loanDate = $loanData['loanDate'];
                // dd($loanDate);
                $monthlyInterest = $loanValue * $interestRate/100;

                if($transRestInterest < $monthlyInterest)
                {
                    $getSmallLoan = $transRestInterest / ($interestRate / 100);
                    $monthlyLateFee = $getSmallLoan * $lateFeeRate/100;
                    $dailyLateFee = $monthlyLateFee/30;

                }else{

                    $monthlyLateFee = $loanValue * $lateFeeRate/100;
                    $dailyLateFee = $monthlyLateFee/30;

                }


                $startDate = Carbon::parse($getTransactionData->paidDate);
                $endDate = Carbon::parse($data->paidDate);

                if ($startDate->year == $endDate->year && $startDate->month == $endDate->month) {

                    $loanDate = Carbon::parse($loanDate);
                    $currentMonthPayDate =  $endDate->day($loanDate->day);
                    $endDate = Carbon::parse($data->paidDate);
                    if($currentMonthPayDate < $endDate && $currentMonthPayDate <= $startDate){
                        if($currentMonthPayDate == $startDate){
                            $totalInterest = $monthlyInterest;
                        }
                         echo "h";
                        //  dd('o');
                        $startDate = Carbon::parse($getTransactionData->paidDate);
                    }elseif($currentMonthPayDate > $endDate && $currentMonthPayDate >= $startDate){
                        if($currentMonthPayDate == $startDate){
                            $totalInterest = $monthlyInterest;
                        }
                         echo "h5";
                        //  dd('o');
                        $startDate = Carbon::parse($getTransactionData->paidDate);
                    }elseif($currentMonthPayDate < $endDate && $currentMonthPayDate >= $startDate){
                        if($currentMonthPayDate == $startDate){
                            $totalInterest = $monthlyInterest;
                        }
                        //  echo "hj";
                        // dd('o');
                        $totalInterest = $monthlyInterest;
                        $startDate = $currentMonthPayDate;
                    }
                } else {
                    //dd($lateFeeForSmallLoan);
                    $loanDate = Carbon::parse($loanDate);
                    $startDate2 = Carbon::parse($getTransactionData->paidDate);
                    $currentMonthPayDate =  $startDate2->day($loanDate->day);
                    $endDate = Carbon::parse($data->paidDate);
                    //  dd($currentMonthPayDate,$startDate);

                    if($currentMonthPayDate <= $startDate){

                        $nextMonthPayDate = $currentMonthPayDate->addMonth();
                        if($nextMonthPayDate >= $endDate){
                            // dd("ll");

                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }else{
                            $startDate = $nextMonthPayDate;
                        }
                // addone month to currentmdate
                    }else{
                        $startDate = $currentMonthPayDate;
                    }


                }


                // dd($currentMonthPayDate,$startDate,$endDate);
                // Calculate the difference between loan date and transaction date
                $yearsGap = $startDate->diffInYears($endDate);
                $monthsGap = $startDate->addYears($yearsGap)->diffInMonths($endDate);
                $daysGap = $startDate->addMonths($monthsGap)->diffInDays($endDate);

                 //according to addingTransaction method add or minus value


                        //  dd($monthsGap,$yearsGap,$daysGap);


                $endDateMonthPayDate =  $endDate->day($loanDate->day);
                $endDate = Carbon::parse($data->paidDate);
                $subOrAddDays = 0;
                if ($endDateMonthPayDate >= $endDate){
                    // dd($endDateMonthPayDate,$endDate);
                    // echo "sss";
                    $givenDate = Carbon::parse($endDateMonthPayDate);
                    // Get the number of days in the previous month
                    $numberOfDaysInPreviousMonth = $givenDate->subMonthNoOverflow()->daysInMonth;

                    if ($numberOfDaysInPreviousMonth == 31){
                        echo"tttt";
                        $startDateDay = $startDate->day;
                        if($startDateDay == 31 ){
                            $subOrAddDays = 0;
                        }else{
                            $subOrAddDays = -1;
                        }



                    }
                    elseif($numberOfDaysInPreviousMonth == 28){
                        echo"oooooo";
                        $subOrAddDays = 2;

                    }
                    elseif($numberOfDaysInPreviousMonth == 29){
                         echo"iiiiii";
                         $subOrAddDays = 1;

                    }
                }
                else{
                    // transaction date
                    // Get the day from transaction date
                    $endDateDay = $endDate->day;
                // dd($endDate);
                    if($endDateDay == 31){
                         echo"uuuuuu";
                         $subOrAddDays = -1;
                    }else{
                         echo"xxxx";
                         $subOrAddDays = 0;
                    }
                }


                $daysGap = $daysGap + $subOrAddDays;
                // dd($monthsGap,$yearsGap,$daysGap);







                $startDate = Carbon::parse($getTransactionData->paidDate);
                $endDate = Carbon::parse($data->paidDate);

                if ($startDate->year == $endDate->year && $startDate->month == $endDate->month) {

                    $loanDate = Carbon::parse($loanDate);
                    $currentMonthPayDate =  $endDate->day($loanDate->day);
                    $endDate = Carbon::parse($data->paidDate);
                    if($currentMonthPayDate < $endDate && $currentMonthPayDate <= $startDate){
                        if($currentMonthPayDate == $startDate){
                            $totalInterest = $monthlyInterest;
                        }
                            echo "h";
                        //  dd('o');
                        $startDate = Carbon::parse($getTransactionData->paidDate);
                    }elseif($currentMonthPayDate > $endDate && $currentMonthPayDate >= $startDate){
                        if($currentMonthPayDate == $startDate){
                            $totalInterest = $monthlyInterest;
                        }
                            echo "h5";
                            // dd('o');
                        $startDate = Carbon::parse($getTransactionData->paidDate);
                    }elseif($currentMonthPayDate < $endDate && $currentMonthPayDate >= $startDate){
                        if($currentMonthPayDate == $startDate){
                            $totalInterest = $monthlyInterest;
                        }
                        //  echo "hj";
                        // dd('o');
                        $totalInterest = $monthlyInterest;
                        $startDate = $currentMonthPayDate;
                    }
                } else {
                    //dd($lateFeeForSmallLoan);
                    $loanDate = Carbon::parse($loanDate);
                    $startDate2 = Carbon::parse($getTransactionData->paidDate);
                    $currentMonthPayDate =  $startDate2->day($loanDate->day);
                    $endDate = Carbon::parse($data->paidDate);
                    //  dd($currentMonthPayDate,$startDate);

                    if($currentMonthPayDate <= $startDate){
                        $nextMonthPayDate = $currentMonthPayDate->addMonth();
                        if($nextMonthPayDate >= $endDate){
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }else{
                            $startDate = $nextMonthPayDate;
                        }
                    }else{
                        $startDate = $currentMonthPayDate;
                    }


                }






                //Total Interest calculation
                $totalInterest = $totalInterest + $monthlyInterest * 12 * $yearsGap;
                $totalInterest = $totalInterest + $monthlyInterest * $monthsGap;
                $totalInterest = $totalInterest + $transRestInterest;

                // dd($lateFeeForSmallLoan);
                if(!$lateFeeForSmallLoan){


                    $this->SecondTransCalcInterest($totalInterest);

                }

                //on monthly loan pay date will not add monthly interest, after that date add monthly interest value
                if(($monthsGap <> 0 || $yearsGap <> 0) && $daysGap <> 0){
                    $totalInterest = $totalInterest + $monthlyInterest;
                    //   dd($totalInterest);
                }else{

                }
                // dd($totalInterest);

                            //Total late fees calculation
                            //month diff is more than or 1, calculate late fees
                        //     $startDate = Carbon::parse($getTransactionData->paidDate);
                        //    $loanDate = Carbon::parse($loanDate);
                        //    $endDate = Carbon::parse($data->paidDate);
                        //     $currentMonthPayDate =  $endDate->day($loanDate->day);
                        // $startDate = Carbon::parse($getTransactionData->paidDate);
                //   dd($currentMonthPayDate,$endDate);
                $monthsDifference = $currentMonthPayDate->diffInMonths($endDate);


                if($monthsDifference >= 1){

                    $totalLateFee = $monthlyLateFee * 12 * $yearsGap;
                    echo "$monthlyLateFee";
                    $totalLateFee = $totalLateFee + $monthlyLateFee * $monthsGap;
                    echo "$totalLateFee";
                    $totalLateFee = $totalLateFee + $dailyLateFee * $daysGap;
                    echo "$totalLateFee";


                    $totalLateFee = $totalLateFee + $transRestLateFee;

                }elseif($monthsDifference == 0){

                    if($totalInterest > $monthlyInterest && $totalInterest < (2  * $monthlyInterest)){
                        $totalLateFee = $transRestLateFee +  $this->lateFeeForSmallLoan;

                    }else{
                        // dd('hio',$transRestLateFee);
                        if(!$lateFeeForSmallLoan){
                            //  dd('hio',$transRestLateFee);
                            if($totalInterest >= 2  * $monthlyInterest){
                                $totalLateFee = $transRestLateFee + ($dailyLateFee * $daysGap);
                                // dd($totalLateFee);
                            }else{
                                $totalLateFee = $transRestLateFee;
                                // dd('hio',$transRestLateFee);
                            }

                        }else{
                            // dd('hio',$transRestLateFee);
                            $totalLateFee = $transRestLateFee;
                        }

                        // + $this->lateFeeForSmallLoan ;
                // dd('ghg',$monthsDifference,$totalLateFee);
                    }

                    // dd( $totalLateFee);
                // dd($transRestLateFee,$totalLateFee,$this->lateFeeForSmallLoan);
                }

                //Add late fees to DB $transPaidLateFee - $transRestLateFee
                if($transPaidAmount >= $totalLateFee){

                    $transPaidLateFee = $totalLateFee;
                    $transRestLateFee = 0;
                    $transPaidAmount = $transPaidAmount - $totalLateFee;

                }
                else{

                    $transPaidLateFee = $transPaidAmount;
                    $transRestLateFee = $totalLateFee - $transPaidAmount;
                    $transPaidAmount = 0;

                }


                //Add interest to DB
                if($transPaidAmount >= $totalInterest){

                    $transPaidInterest = $totalInterest;
                    $transRestInterest = 0;
                    $transPaidAmount= $transPaidAmount - $totalInterest;
                }
                else{

                    $transPaidInterest = $transPaidAmount;
                    $transRestInterest = $totalInterest - $transPaidAmount;
                    $transPaidAmount = 0;

                }


                //Add extra money or reduce from loan
                if($data->extraMoney == "keep" && $transPaidAmount > 0 ){
                    $transExtraMoney = $transPaidAmount;
                }
                elseif($data->extraMoney == "reduce" && $transPaidAmount > 0 ) {
                    $transReducedAmount = $transPaidAmount;

                    // update loan value by reducing
                    Loan::where('loanID', $data->transLoanID)
                    ->decrement('loanAmount', $transReducedAmount);

                }
                // dd("paidamount  $data->transPaidAmount","latFeesForSmallInt $lateFeeForSmallLoan" ,"RestLateFee $transRestLateFee","RestInterest $transRestInterest","Extram $transExtraMoney","Paid Latefee $transPaidLateFee");

                // Create a new instance of the Transaction model
                $storeToTransaction = new Transaction();

                // Set the values for each column based on your data
                $storeToTransaction->paidDate = $data->paidDate;
                $storeToTransaction->transDetails = $data->transDetails;
                $storeToTransaction->transPaidAmount = $data->transPaidAmount;
                $storeToTransaction->transAllPaid =  $data->transPaidAmount + $getTransactionData->transAllPaid;
                $storeToTransaction->transPaidInterest = $transPaidInterest;
                $storeToTransaction->transPaidPenaltyFee = $transPaidLateFee;
                $storeToTransaction->transRestInterest = $transRestInterest;
                $storeToTransaction->transRestPenaltyFee = $transRestLateFee;
                $storeToTransaction->transReducedAmount = $transReducedAmount;
                $storeToTransaction->transExtraMoney = $transExtraMoney;
                $storeToTransaction->transLoanID = $data->transLoanID;


                // Save the model to the database
                $storeToTransaction->save();


            } else {
                $lastDate = $newTransDate2->subDay();
                $payDateOldTransDate =  $oldTransDate->day($loanDate->day);
                $payDateNewTransDate =  $newTransDate->day($loanDate->day);

                // dd($lastDate,$payDateOldTransDate,$payDateNewTransDate);

                if ($lastDate->notEqualTo($payDateOldTransDate) && $lastDate->notEqualTo($payDateNewTransDate)) {
                    // Your code when the dates are not equal
                    $loanDate = Carbon::parse($this->loanDate);
                    $newTransDate = Carbon::parse($lastDate);
                    // echo "{$startDate} - {$endDate}<br><br>";
                    $payDateNewTransDate =  $newTransDate->day($loanDate->day)->toDateString();
                    $lateFeeForSmallLoan = 0;
                    if($payDateNewTransDate > $loanDate){
                        $newTransDate = Carbon::parse($data->paidDate);
                        $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                        $payDateOldTransDate =  $oldTransDate->day($loanDate->day)->toDateString();
                        // Calculate the $newTransDate, oldTransDate difference in days
                        $diffInDaysOldTransNewTransDates = $newTransDate->diffInDays($oldTransDate);
                        //  dd($diffInDaysOldTransNewTransDates,$newTransDate,$oldTransDate);
                        //Get loan data from db
                        $loanData = Loan::where('loanID', $data->transLoanID)
                        ->get()->first();

                        $loanValue = $loanData['loanAmount'];
                        $interestRate = $loanData['loanRate'];
                        $lateFeeRate = $loanData['penaltyRate'];

                        $monthlyInterest = $loanValue * $interestRate/100;



                        if($diffInDaysOldTransNewTransDates >= 30){



                            $loanDate = Carbon::parse($this->loanDate);
                            $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                            $payDateOldTransDate =  $oldTransDate->day($loanDate->day);
                            $oldTransDate = Carbon::parse($getTransactionData->paidDate);


                            if($payDateOldTransDate > $oldTransDate){

                                if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                    $diffOldTransAndPayDateOldTransDates = $oldTransDate->diff($payDateOldTransDate);
                                    $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                    $getSmallLoan = $getSmallInterest * ($interestRate / 100);
                                    $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                    $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                    $lateFeeForSmallLoan = $diffOldTransAndPayDateOldTransDates->d * $getDailyLateFeeForSmallLoan;
                                }
                            }elseif($payDateOldTransDate == $oldTransDate){
                                //
                            }elseif($payDateOldTransDate < $oldTransDate){
                                echo "sdf";
                                if(($getTransactionData->transRestInterest ) > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                    $nextPayDateOldTransDate =  $oldTransDate->day($loanDate->day)->addMonth()->toDateString();
                                    $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                                    echo "{$nextPayDateOldTransDate}- { $oldTransDate}<br>";
                                    $diffOldTransAndNextPayDateOldTransDates = $oldTransDate->diffInDays($nextPayDateOldTransDate);
                                    echo "{$diffOldTransAndNextPayDateOldTransDates}<br>";


                                    if($oldTransDate->daysInMonth == 31){
                                        $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates - 1;
                                    }elseif($oldTransDate->daysInMonth == 28){
                                        $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates + 2;
                                    }elseif($oldTransDate->daysInMonth == 29){
                                        $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates + 1;
                                    }

                                     //dd($diffOldTransAndNextPayDateOldTransDates);
                                    $getSmallInterest = ($getTransactionData->transRestInterest) - $monthlyInterest;
                                    echo "{$getSmallInterest}<br>";

                                    $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                    echo "{$getSmallLoan}<br>";

                                    $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                    $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                    $lateFeeForSmallLoan = $diffOldTransAndNextPayDateOldTransDates * $getDailyLateFeeForSmallLoan;


                                }
                            }
                        }else{
                            $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                            if ($newTransDate->month == $oldTransDate->month){

                                if($oldTransDate < $payDateOldTransDate && $payDateOldTransDate < $newTransDate){

                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndPayDateOldTransDates = $oldTransDate->diffInDays($payDateOldTransDate);

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateOldTransDates * $getDailyLateFeeForSmallLoan;
                                    }

                                }elseif($oldTransDate < $newTransDate && $newTransDate <= $payDateOldTransDate){

                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                                    }

                                }elseif($payDateOldTransDate <= $oldTransDate && $oldTransDate < $newTransDate){


                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);
                                        // dd("htu",$diffInDaysOldTransAndNewTransDates,$oldTransDate,$newTransDate);
                                        if($newTransDate->day == 31){
                                            $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates - 1;
                                        }

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                                    }


                                }

                            }else{
                                if($oldTransDate < $payDateOldTransDate){
                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndPayDateOldTransDates = $oldTransDate->diffInDays($payDateOldTransDate);

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateOldTransDates * $getDailyLateFeeForSmallLoan;
                                    }
                                }else{
                                    if($newTransDate <= $payDateNewTransDate){
                                        if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                            $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);


                                            if($oldTransDate->daysInMonth == 31){
                                                $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates - 1;
                                            }elseif($oldTransDate->daysInMonth == 28){
                                                $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates + 2;
                                            }elseif($oldTransDate->daysInMonth == 29){
                                                $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates + 1;
                                            }

                                            $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                            $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                            $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                            $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                            $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                                        }
                                    }elseif($payDateNewTransDate < $newTransDate){
                                        if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                            $diffInDaysOldTransAndPayDateNewTransDates = $oldTransDate->diffInDays($payDateNewTransDate);

                                            if($oldTransDate->daysInMonth == 31){
                                                $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates - 1;
                                            }elseif($oldTransDate->daysInMonth == 28){
                                                $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates + 2;
                                            }elseif($oldTransDate->daysInMonth == 29){
                                                $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates + 1;
                                            }

                                            $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                            $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                            $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                            $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                            $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateNewTransDates * $getDailyLateFeeForSmallLoan;
                                        }
                                    }
                                }
                            }
                        }
                    }


                    // assign variables
                    $transPaidAmount = $data->transPaidAmount + $getTransactionData->transExtraMoney;
                    $transPaidLateFee = $data->transPaidPenaltyFee;
                    $transRestLateFee = $lateFeeForSmallLoan + $getTransactionData->transRestPenaltyFee;
                    $transPaidInterest = $data->transPaidInterest;
                    $transRestInterest = $getTransactionData->transRestInterest;
                    $transExtraMoney = 0;
                    $transReducedAmount = 0;
                    $totalInterest = 0;
                    $this->lateFeeForSmallLoan = 0;


                    // dd($lateFeeForSmallLoan);

                    //Get loan data from db
                    $loanData = Loan::where('loanID', $data->transLoanID)
                    ->get()->first();

                    $loanValue = $loanData['loanAmount'];
                    $interestRate = $loanData['loanRate'];
                    $lateFeeRate = $loanData['penaltyRate'];
                    $loanDate = $loanData['loanDate'];
                    // dd($loanDate);
                    $monthlyInterest = $loanValue * $interestRate/100;

                    if($transRestInterest < $monthlyInterest)
                    {
                        $getSmallLoan = $transRestInterest / ($interestRate / 100);
                        $monthlyLateFee = $getSmallLoan * $lateFeeRate/100;
                        $dailyLateFee = $monthlyLateFee/30;

                    }else{

                        $monthlyLateFee = $loanValue * $lateFeeRate/100;
                        $dailyLateFee = $monthlyLateFee/30;

                    }


                    $startDate = Carbon::parse($getTransactionData->paidDate);
                    $endDate = Carbon::parse($lastDate);

                    if ($startDate->year == $endDate->year && $startDate->month == $endDate->month) {

                        $loanDate = Carbon::parse($loanDate);
                        $currentMonthPayDate =  $endDate->day($loanDate->day);
                        $endDate = Carbon::parse($lastDate);
                        if($currentMonthPayDate < $endDate && $currentMonthPayDate <= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                             echo "h";
                            //  dd('o');
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }elseif($currentMonthPayDate > $endDate && $currentMonthPayDate >= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                             echo "h5";
                            //  dd('o');
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }elseif($currentMonthPayDate < $endDate && $currentMonthPayDate >= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                            //  echo "hj";
                            // dd('o');
                            $totalInterest = $monthlyInterest;
                            $startDate = $currentMonthPayDate;
                        }
                    } else {
                        //dd($lateFeeForSmallLoan);
                        $loanDate = Carbon::parse($loanDate);
                        $startDate2 = Carbon::parse($getTransactionData->paidDate);
                        $currentMonthPayDate =  $startDate2->day($loanDate->day);
                        $endDate = Carbon::parse($lastDate);
                        //  dd($currentMonthPayDate,$startDate);

                        if($currentMonthPayDate <= $startDate){

                            $nextMonthPayDate = $currentMonthPayDate->addMonth();
                            if($nextMonthPayDate >= $endDate){
                                // dd("ll");

                                $startDate = Carbon::parse($getTransactionData->paidDate);
                            }else{
                                $startDate = $nextMonthPayDate;
                            }
                    // addone month to currentmdate
                        }else{
                            $startDate = $currentMonthPayDate;
                        }


                    }


                    // dd($currentMonthPayDate,$startDate,$endDate);
                    // Calculate the difference between loan date and transaction date
                    $yearsGap = $startDate->diffInYears($endDate);
                    $monthsGap = $startDate->addYears($yearsGap)->diffInMonths($endDate);
                    $daysGap = $startDate->addMonths($monthsGap)->diffInDays($endDate);

                     //according to addingTransaction method add or minus value


                            //  dd($monthsGap,$yearsGap,$daysGap);


                    $endDateMonthPayDate =  $endDate->day($loanDate->day);
                    $endDate = Carbon::parse($lastDate);
                    $subOrAddDays = 0;
                    if ($endDateMonthPayDate >= $endDate){
                        // dd($endDateMonthPayDate,$endDate);
                        // echo "sss";
                        $givenDate = Carbon::parse($endDateMonthPayDate);
                        // Get the number of days in the previous month
                        $numberOfDaysInPreviousMonth = $givenDate->subMonthNoOverflow()->daysInMonth;

                        if ($numberOfDaysInPreviousMonth == 31){
                            echo"tttt";
                            $startDateDay = $startDate->day;
                            if($startDateDay == 31 ){
                                $subOrAddDays = 0;
                            }else{
                                $subOrAddDays = -1;
                            }



                        }
                        elseif($numberOfDaysInPreviousMonth == 28){
                            echo"oooooo";
                            $subOrAddDays = 2;

                        }
                        elseif($numberOfDaysInPreviousMonth == 29){
                             echo"iiiiii";
                             $subOrAddDays = 1;

                        }
                    }
                    else{
                        // transaction date
                        // Get the day from transaction date
                        $endDateDay = $endDate->day;
                    // dd($endDate);
                        if($endDateDay == 31){
                             echo"uuuuuu";
                             $subOrAddDays = -1;
                        }else{
                             echo"xxxx";
                             $subOrAddDays = 0;
                        }
                    }


                    $daysGap = $daysGap + $subOrAddDays;
                    // dd($monthsGap,$yearsGap,$daysGap);







                    $startDate = Carbon::parse($getTransactionData->paidDate);
                    $endDate = Carbon::parse($lastDate);

                    if ($startDate->year == $endDate->year && $startDate->month == $endDate->month) {

                        $loanDate = Carbon::parse($loanDate);
                        $currentMonthPayDate =  $endDate->day($loanDate->day);
                        $endDate = Carbon::parse($lastDate);
                        if($currentMonthPayDate < $endDate && $currentMonthPayDate <= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                                echo "h";
                            //  dd('o');
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }elseif($currentMonthPayDate > $endDate && $currentMonthPayDate >= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                                echo "h5";
                                // dd('o');
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }elseif($currentMonthPayDate < $endDate && $currentMonthPayDate >= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                            //  echo "hj";
                            // dd('o');
                            $totalInterest = $monthlyInterest;
                            $startDate = $currentMonthPayDate;
                        }
                    } else {
                        //dd($lateFeeForSmallLoan);
                        $loanDate = Carbon::parse($loanDate);
                        $startDate2 = Carbon::parse($getTransactionData->paidDate);
                        $currentMonthPayDate =  $startDate2->day($loanDate->day);
                        $endDate = Carbon::parse($lastDate);
                        //  dd($currentMonthPayDate,$startDate);

                        if($currentMonthPayDate <= $startDate){
                            $nextMonthPayDate = $currentMonthPayDate->addMonth();
                            if($nextMonthPayDate >= $endDate){
                                $startDate = Carbon::parse($getTransactionData->paidDate);
                            }else{
                                $startDate = $nextMonthPayDate;
                            }
                        }else{
                            $startDate = $currentMonthPayDate;
                        }


                    }






                    //Total Interest calculation
                    $totalInterest = $totalInterest + $monthlyInterest * 12 * $yearsGap;
                    $totalInterest = $totalInterest + $monthlyInterest * $monthsGap;
                    $totalInterest = $totalInterest + $transRestInterest;

                    // dd($lateFeeForSmallLoan);
                    if(!$lateFeeForSmallLoan){


                        $this->SecondTransCalcInterest($totalInterest);

                    }

                    //on monthly loan pay date will not add monthly interest, after that date add monthly interest value
                    if(($monthsGap <> 0 || $yearsGap <> 0) && $daysGap <> 0){
                        $totalInterest = $totalInterest + $monthlyInterest;
                        //   dd($totalInterest);
                    }else{

                    }
                    // dd($totalInterest);

                                //Total late fees calculation
                                //month diff is more than or 1, calculate late fees
                            //     $startDate = Carbon::parse($getTransactionData->paidDate);
                            //    $loanDate = Carbon::parse($loanDate);
                            //    $endDate = Carbon::parse($lastDate);
                            //     $currentMonthPayDate =  $endDate->day($loanDate->day);
                            // $startDate = Carbon::parse($getTransactionData->paidDate);
                    //   dd($currentMonthPayDate,$endDate);
                    $monthsDifference = $currentMonthPayDate->diffInMonths($endDate);


                    if($monthsDifference >= 1){

                        $totalLateFee = $monthlyLateFee * 12 * $yearsGap;
                        echo "$monthlyLateFee";
                        $totalLateFee = $totalLateFee + $monthlyLateFee * $monthsGap;
                        echo "$totalLateFee";
                        $totalLateFee = $totalLateFee + $dailyLateFee * $daysGap;
                        echo "$totalLateFee";


                        $totalLateFee = $totalLateFee + $transRestLateFee;

                    }elseif($monthsDifference == 0){

                        if($totalInterest > $monthlyInterest && $totalInterest < (2  * $monthlyInterest)){
                            $totalLateFee = $transRestLateFee +  $this->lateFeeForSmallLoan;

                        }else{
                            // dd('hio',$transRestLateFee);
                            if(!$lateFeeForSmallLoan){
                                //  dd('hio',$transRestLateFee);
                                if($totalInterest >= 2  * $monthlyInterest){
                                    $totalLateFee = $transRestLateFee + ($dailyLateFee * $daysGap);
                                    // dd($totalLateFee);
                                }else{
                                    $totalLateFee = $transRestLateFee;
                                    // dd('hio',$transRestLateFee);
                                }

                            }else{
                                // dd('hio',$transRestLateFee);
                                $totalLateFee = $transRestLateFee;
                            }

                            // + $this->lateFeeForSmallLoan ;
                    // dd('ghg',$monthsDifference,$totalLateFee);
                        }

                        // dd( $totalLateFee);
                    // dd($transRestLateFee,$totalLateFee,$this->lateFeeForSmallLoan);
                    }

                    //Add late fees to DB $transPaidLateFee - $transRestLateFee
                    if($transPaidAmount >= $totalLateFee){

                        $transPaidLateFee = $totalLateFee;
                        $transRestLateFee = 0;
                        $transPaidAmount = $transPaidAmount - $totalLateFee;

                    }
                    else{

                        $transPaidLateFee = $transPaidAmount;
                        $transRestLateFee = $totalLateFee - $transPaidAmount;
                        $transPaidAmount = 0;

                    }


                    //Add interest to DB
                    if($transPaidAmount >= $totalInterest){

                        $transPaidInterest = $totalInterest;
                        $transRestInterest = 0;
                        $transPaidAmount= $transPaidAmount - $totalInterest;
                    }
                    else{

                        $transPaidInterest = $transPaidAmount;
                        $transRestInterest = $totalInterest - $transPaidAmount;
                        $transPaidAmount = 0;

                    }


                    //Add extra money or reduce from loan
                    if($data->extraMoney == "keep" && $transPaidAmount > 0 ){
                        $transExtraMoney = $transPaidAmount;
                    }
                    elseif($data->extraMoney == "reduce" && $transPaidAmount > 0 ) {
                        $transReducedAmount = $transPaidAmount;

                        // update loan value by reducing
                        Loan::where('loanID', $data->transLoanID)
                        ->decrement('loanAmount', $transReducedAmount);

                    }
                    // dd("paidamount  $data->transPaidAmount","latFeesForSmallInt $lateFeeForSmallLoan" ,"RestLateFee $transRestLateFee","RestInterest $transRestInterest","Extram $transExtraMoney","Paid Latefee $transPaidLateFee");

                    // Create a new instance of the Transaction model
                    $storeToTransaction = new Transaction();

                    // Set the values for each column based on your data
                    $storeToTransaction->paidDate = $lastDate;
                    $storeToTransaction->transDetails = $data->transDetails;
                    $storeToTransaction->transPaidAmount = $data->transPaidAmount;
                    $storeToTransaction->transAllPaid =  $data->transPaidAmount + $getTransactionData->transAllPaid;
                    $storeToTransaction->transPaidInterest = $transPaidInterest;
                    $storeToTransaction->transPaidPenaltyFee = $transPaidLateFee;
                    $storeToTransaction->transRestInterest = $transRestInterest;
                    $storeToTransaction->transRestPenaltyFee = $transRestLateFee;
                    $storeToTransaction->transReducedAmount = $transReducedAmount;
                    $storeToTransaction->transExtraMoney = $transExtraMoney;
                    $storeToTransaction->transLoanID = $data->transLoanID;


                    // Save the model to the database
                    $storeToTransaction->save();


                    ///
                    //////
                    //////
                    //////

                    $loanDate = Carbon::parse($this->loanDate);
                    $newTransDate = Carbon::parse($data->paidDate);
                    // echo "{$startDate} - {$endDate}<br><br>";
                    $payDateNewTransDate =  $newTransDate->day($loanDate->day)->toDateString();
                    $lateFeeForSmallLoan = 0;
                    if($payDateNewTransDate > $loanDate){
                        $newTransDate = Carbon::parse($data->paidDate);
                        $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                        $payDateOldTransDate =  $oldTransDate->day($loanDate->day)->toDateString();
                        // Calculate the $newTransDate, oldTransDate difference in days
                        $diffInDaysOldTransNewTransDates = $newTransDate->diffInDays($oldTransDate);
                        //  dd($diffInDaysOldTransNewTransDates,$newTransDate,$oldTransDate);
                        //Get loan data from db
                        $loanData = Loan::where('loanID', $data->transLoanID)
                        ->get()->first();

                        $loanValue = $loanData['loanAmount'];
                        $interestRate = $loanData['loanRate'];
                        $lateFeeRate = $loanData['penaltyRate'];

                        $monthlyInterest = $loanValue * $interestRate/100;



                        if($diffInDaysOldTransNewTransDates >= 30){



                            $loanDate = Carbon::parse($this->loanDate);
                            $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                            $payDateOldTransDate =  $oldTransDate->day($loanDate->day);
                            $oldTransDate = Carbon::parse($getTransactionData->paidDate);


                            if($payDateOldTransDate > $oldTransDate){

                                if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                    $diffOldTransAndPayDateOldTransDates = $oldTransDate->diff($payDateOldTransDate);
                                    $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                    $getSmallLoan = $getSmallInterest * ($interestRate / 100);
                                    $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                    $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                    $lateFeeForSmallLoan = $diffOldTransAndPayDateOldTransDates->d * $getDailyLateFeeForSmallLoan;
                                }
                            }elseif($payDateOldTransDate == $oldTransDate){
                                //
                            }elseif($payDateOldTransDate < $oldTransDate){
                                echo "sdf";
                                if(($getTransactionData->transRestInterest ) > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                    $nextPayDateOldTransDate =  $oldTransDate->day($loanDate->day)->addMonth()->toDateString();
                                    $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                                    echo "{$nextPayDateOldTransDate}- { $oldTransDate}<br>";
                                    $diffOldTransAndNextPayDateOldTransDates = $oldTransDate->diffInDays($nextPayDateOldTransDate);
                                    echo "{$diffOldTransAndNextPayDateOldTransDates}<br>";


                                    if($oldTransDate->daysInMonth == 31){
                                        $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates - 1;
                                    }elseif($oldTransDate->daysInMonth == 28){
                                        $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates + 2;
                                    }elseif($oldTransDate->daysInMonth == 29){
                                        $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates + 1;
                                    }

                                     //dd($diffOldTransAndNextPayDateOldTransDates);
                                    $getSmallInterest = ($getTransactionData->transRestInterest) - $monthlyInterest;
                                    echo "{$getSmallInterest}<br>";

                                    $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                    echo "{$getSmallLoan}<br>";

                                    $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                    $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                    $lateFeeForSmallLoan = $diffOldTransAndNextPayDateOldTransDates * $getDailyLateFeeForSmallLoan;


                                }
                            }
                        }else{
                            $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                            if ($newTransDate->month == $oldTransDate->month){

                                if($oldTransDate < $payDateOldTransDate && $payDateOldTransDate < $newTransDate){

                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndPayDateOldTransDates = $oldTransDate->diffInDays($payDateOldTransDate);

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateOldTransDates * $getDailyLateFeeForSmallLoan;
                                    }

                                }elseif($oldTransDate < $newTransDate && $newTransDate <= $payDateOldTransDate){

                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                                    }

                                }elseif($payDateOldTransDate <= $oldTransDate && $oldTransDate < $newTransDate){


                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);
                                        // dd("htu",$diffInDaysOldTransAndNewTransDates,$oldTransDate,$newTransDate);
                                        if($newTransDate->day == 31){
                                            $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates - 1;
                                        }

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                                    }


                                }

                            }else{
                                if($oldTransDate < $payDateOldTransDate){
                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndPayDateOldTransDates = $oldTransDate->diffInDays($payDateOldTransDate);

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateOldTransDates * $getDailyLateFeeForSmallLoan;
                                    }
                                }else{
                                    if($newTransDate <= $payDateNewTransDate){
                                        if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                            $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);


                                            if($oldTransDate->daysInMonth == 31){
                                                $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates - 1;
                                            }elseif($oldTransDate->daysInMonth == 28){
                                                $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates + 2;
                                            }elseif($oldTransDate->daysInMonth == 29){
                                                $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates + 1;
                                            }

                                            $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                            $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                            $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                            $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                            $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                                        }
                                    }elseif($payDateNewTransDate < $newTransDate){
                                        if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                            $diffInDaysOldTransAndPayDateNewTransDates = $oldTransDate->diffInDays($payDateNewTransDate);

                                            if($oldTransDate->daysInMonth == 31){
                                                $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates - 1;
                                            }elseif($oldTransDate->daysInMonth == 28){
                                                $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates + 2;
                                            }elseif($oldTransDate->daysInMonth == 29){
                                                $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates + 1;
                                            }

                                            $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                            $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                            $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                            $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                            $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateNewTransDates * $getDailyLateFeeForSmallLoan;
                                        }
                                    }
                                }
                            }
                        }
                    }


                    // assign variables
                    $transPaidAmount = $data->transPaidAmount + $getTransactionData->transExtraMoney;
                    $transPaidLateFee = $data->transPaidPenaltyFee;
                    $transRestLateFee = $lateFeeForSmallLoan + $getTransactionData->transRestPenaltyFee;
                    $transPaidInterest = $data->transPaidInterest;
                    $transRestInterest = $getTransactionData->transRestInterest;
                    $transExtraMoney = 0;
                    $transReducedAmount = 0;
                    $totalInterest = 0;
                    $this->lateFeeForSmallLoan = 0;


                    // dd($lateFeeForSmallLoan);

                    //Get loan data from db
                    $loanData = Loan::where('loanID', $data->transLoanID)
                    ->get()->first();

                    $loanValue = $loanData['loanAmount'];
                    $interestRate = $loanData['loanRate'];
                    $lateFeeRate = $loanData['penaltyRate'];
                    $loanDate = $loanData['loanDate'];
                    // dd($loanDate);
                    $monthlyInterest = $loanValue * $interestRate/100;

                    if($transRestInterest < $monthlyInterest)
                    {
                        $getSmallLoan = $transRestInterest / ($interestRate / 100);
                        $monthlyLateFee = $getSmallLoan * $lateFeeRate/100;
                        $dailyLateFee = $monthlyLateFee/30;

                    }else{

                        $monthlyLateFee = $loanValue * $lateFeeRate/100;
                        $dailyLateFee = $monthlyLateFee/30;

                    }


                    $startDate = Carbon::parse($getTransactionData->paidDate);
                    $endDate = Carbon::parse($data->paidDate);

                    if ($startDate->year == $endDate->year && $startDate->month == $endDate->month) {

                        $loanDate = Carbon::parse($loanDate);
                        $currentMonthPayDate =  $endDate->day($loanDate->day);
                        $endDate = Carbon::parse($data->paidDate);
                        if($currentMonthPayDate < $endDate && $currentMonthPayDate <= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                             echo "h";
                            //  dd('o');
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }elseif($currentMonthPayDate > $endDate && $currentMonthPayDate >= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                             echo "h5";
                            //  dd('o');
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }elseif($currentMonthPayDate < $endDate && $currentMonthPayDate >= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                            //  echo "hj";
                            // dd('o');
                            $totalInterest = $monthlyInterest;
                            $startDate = $currentMonthPayDate;
                        }
                    } else {
                        //dd($lateFeeForSmallLoan);
                        $loanDate = Carbon::parse($loanDate);
                        $startDate2 = Carbon::parse($getTransactionData->paidDate);
                        $currentMonthPayDate =  $startDate2->day($loanDate->day);
                        $endDate = Carbon::parse($data->paidDate);
                        //  dd($currentMonthPayDate,$startDate);

                        if($currentMonthPayDate <= $startDate){

                            $nextMonthPayDate = $currentMonthPayDate->addMonth();
                            if($nextMonthPayDate >= $endDate){
                                // dd("ll");

                                $startDate = Carbon::parse($getTransactionData->paidDate);
                            }else{
                                $startDate = $nextMonthPayDate;
                            }
                    // addone month to currentmdate
                        }else{
                            $startDate = $currentMonthPayDate;
                        }


                    }


                    // dd($currentMonthPayDate,$startDate,$endDate);
                    // Calculate the difference between loan date and transaction date
                    $yearsGap = $startDate->diffInYears($endDate);
                    $monthsGap = $startDate->addYears($yearsGap)->diffInMonths($endDate);
                    $daysGap = $startDate->addMonths($monthsGap)->diffInDays($endDate);

                     //according to addingTransaction method add or minus value


                            //  dd($monthsGap,$yearsGap,$daysGap);


                    $endDateMonthPayDate =  $endDate->day($loanDate->day);
                    $endDate = Carbon::parse($data->paidDate);
                    $subOrAddDays = 0;
                    if ($endDateMonthPayDate >= $endDate){
                        // dd($endDateMonthPayDate,$endDate);
                        // echo "sss";
                        $givenDate = Carbon::parse($endDateMonthPayDate);
                        // Get the number of days in the previous month
                        $numberOfDaysInPreviousMonth = $givenDate->subMonthNoOverflow()->daysInMonth;

                        if ($numberOfDaysInPreviousMonth == 31){
                            echo"tttt";
                            $startDateDay = $startDate->day;
                            if($startDateDay == 31 ){
                                $subOrAddDays = 0;
                            }else{
                                $subOrAddDays = -1;
                            }



                        }
                        elseif($numberOfDaysInPreviousMonth == 28){
                            echo"oooooo";
                            $subOrAddDays = 2;

                        }
                        elseif($numberOfDaysInPreviousMonth == 29){
                             echo"iiiiii";
                             $subOrAddDays = 1;

                        }
                    }
                    else{
                        // transaction date
                        // Get the day from transaction date
                        $endDateDay = $endDate->day;
                    // dd($endDate);
                        if($endDateDay == 31){
                             echo"uuuuuu";
                             $subOrAddDays = -1;
                        }else{
                             echo"xxxx";
                             $subOrAddDays = 0;
                        }
                    }


                    $daysGap = $daysGap + $subOrAddDays;
                    // dd($monthsGap,$yearsGap,$daysGap);







                    $startDate = Carbon::parse($getTransactionData->paidDate);
                    $endDate = Carbon::parse($data->paidDate);

                    if ($startDate->year == $endDate->year && $startDate->month == $endDate->month) {

                        $loanDate = Carbon::parse($loanDate);
                        $currentMonthPayDate =  $endDate->day($loanDate->day);
                        $endDate = Carbon::parse($data->paidDate);
                        if($currentMonthPayDate < $endDate && $currentMonthPayDate <= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                                echo "h";
                            //  dd('o');
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }elseif($currentMonthPayDate > $endDate && $currentMonthPayDate >= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                                echo "h5";
                                // dd('o');
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }elseif($currentMonthPayDate < $endDate && $currentMonthPayDate >= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                            //  echo "hj";
                            // dd('o');
                            $totalInterest = $monthlyInterest;
                            $startDate = $currentMonthPayDate;
                        }
                    } else {
                        //dd($lateFeeForSmallLoan);
                        $loanDate = Carbon::parse($loanDate);
                        $startDate2 = Carbon::parse($getTransactionData->paidDate);
                        $currentMonthPayDate =  $startDate2->day($loanDate->day);
                        $endDate = Carbon::parse($data->paidDate);
                        //  dd($currentMonthPayDate,$startDate);

                        if($currentMonthPayDate <= $startDate){
                            $nextMonthPayDate = $currentMonthPayDate->addMonth();
                            if($nextMonthPayDate >= $endDate){
                                $startDate = Carbon::parse($getTransactionData->paidDate);
                            }else{
                                $startDate = $nextMonthPayDate;
                            }
                        }else{
                            $startDate = $currentMonthPayDate;
                        }


                    }






                    //Total Interest calculation
                    $totalInterest = $totalInterest + $monthlyInterest * 12 * $yearsGap;
                    $totalInterest = $totalInterest + $monthlyInterest * $monthsGap;
                    $totalInterest = $totalInterest + $transRestInterest;

                    // dd($lateFeeForSmallLoan);
                    if(!$lateFeeForSmallLoan){


                        $this->SecondTransCalcInterest($totalInterest);

                    }

                    //on monthly loan pay date will not add monthly interest, after that date add monthly interest value
                    if(($monthsGap <> 0 || $yearsGap <> 0) && $daysGap <> 0){
                        $totalInterest = $totalInterest + $monthlyInterest;
                        //   dd($totalInterest);
                    }else{

                    }
                    // dd($totalInterest);

                                //Total late fees calculation
                                //month diff is more than or 1, calculate late fees
                            //     $startDate = Carbon::parse($getTransactionData->paidDate);
                            //    $loanDate = Carbon::parse($loanDate);
                            //    $endDate = Carbon::parse($data->paidDate);
                            //     $currentMonthPayDate =  $endDate->day($loanDate->day);
                            // $startDate = Carbon::parse($getTransactionData->paidDate);
                    //   dd($currentMonthPayDate,$endDate);
                    $monthsDifference = $currentMonthPayDate->diffInMonths($endDate);


                    if($monthsDifference >= 1){

                        $totalLateFee = $monthlyLateFee * 12 * $yearsGap;
                        echo "$monthlyLateFee";
                        $totalLateFee = $totalLateFee + $monthlyLateFee * $monthsGap;
                        echo "$totalLateFee";
                        $totalLateFee = $totalLateFee + $dailyLateFee * $daysGap;
                        echo "$totalLateFee";


                        $totalLateFee = $totalLateFee + $transRestLateFee;

                    }elseif($monthsDifference == 0){

                        if($totalInterest > $monthlyInterest && $totalInterest < (2  * $monthlyInterest)){
                            $totalLateFee = $transRestLateFee +  $this->lateFeeForSmallLoan;

                        }else{
                            // dd('hio',$transRestLateFee);
                            if(!$lateFeeForSmallLoan){
                                //  dd('hio',$transRestLateFee);
                                if($totalInterest >= 2  * $monthlyInterest){
                                    $totalLateFee = $transRestLateFee + ($dailyLateFee * $daysGap);
                                    // dd($totalLateFee);
                                }else{
                                    $totalLateFee = $transRestLateFee;
                                    // dd('hio',$transRestLateFee);
                                }

                            }else{
                                // dd('hio',$transRestLateFee);
                                $totalLateFee = $transRestLateFee;
                            }

                            // + $this->lateFeeForSmallLoan ;
                    // dd('ghg',$monthsDifference,$totalLateFee);
                        }

                        // dd( $totalLateFee);
                    // dd($transRestLateFee,$totalLateFee,$this->lateFeeForSmallLoan);
                    }

                    //Add late fees to DB $transPaidLateFee - $transRestLateFee
                    if($transPaidAmount >= $totalLateFee){

                        $transPaidLateFee = $totalLateFee;
                        $transRestLateFee = 0;
                        $transPaidAmount = $transPaidAmount - $totalLateFee;

                    }
                    else{

                        $transPaidLateFee = $transPaidAmount;
                        $transRestLateFee = $totalLateFee - $transPaidAmount;
                        $transPaidAmount = 0;

                    }


                    //Add interest to DB
                    if($transPaidAmount >= $totalInterest){

                        $transPaidInterest = $totalInterest;
                        $transRestInterest = 0;
                        $transPaidAmount= $transPaidAmount - $totalInterest;
                    }
                    else{

                        $transPaidInterest = $transPaidAmount;
                        $transRestInterest = $totalInterest - $transPaidAmount;
                        $transPaidAmount = 0;

                    }


                    //Add extra money or reduce from loan
                    if($data->extraMoney == "keep" && $transPaidAmount > 0 ){
                        $transExtraMoney = $transPaidAmount;
                    }
                    elseif($data->extraMoney == "reduce" && $transPaidAmount > 0 ) {
                        $transReducedAmount = $transPaidAmount;

                        // update loan value by reducing
                        Loan::where('loanID', $data->transLoanID)
                        ->decrement('loanAmount', $transReducedAmount);

                    }
                    // dd("paidamount  $data->transPaidAmount","latFeesForSmallInt $lateFeeForSmallLoan" ,"RestLateFee $transRestLateFee","RestInterest $transRestInterest","Extram $transExtraMoney","Paid Latefee $transPaidLateFee");

                    // Create a new instance of the Transaction model
                    $storeToTransaction = new Transaction();

                    // Set the values for each column based on your data
                    $storeToTransaction->paidDate = $data->paidDate;
                    $storeToTransaction->transDetails = $data->transDetails;
                    $storeToTransaction->transPaidAmount = $data->transPaidAmount;
                    $storeToTransaction->transAllPaid =  $data->transPaidAmount + $getTransactionData->transAllPaid;
                    $storeToTransaction->transPaidInterest = $transPaidInterest;
                    $storeToTransaction->transPaidPenaltyFee = $transPaidLateFee;
                    $storeToTransaction->transRestInterest = $transRestInterest;
                    $storeToTransaction->transRestPenaltyFee = $transRestLateFee;
                    $storeToTransaction->transReducedAmount = $transReducedAmount;
                    $storeToTransaction->transExtraMoney = $transExtraMoney;
                    $storeToTransaction->transLoanID = $data->transLoanID;


                    // Save the model to the database
                    $storeToTransaction->save();

                } else {
                    // Your code when the dates are equal
                    $lastDate = $lastDate->subDay();

                    $loanDate = Carbon::parse($this->loanDate);
                    $newTransDate = Carbon::parse($lastDate);
                    // echo "{$startDate} - {$endDate}<br><br>";
                    $payDateNewTransDate =  $newTransDate->day($loanDate->day)->toDateString();
                    $lateFeeForSmallLoan = 0;
                    if($payDateNewTransDate > $loanDate){
                        $newTransDate = Carbon::parse($data->paidDate);
                        $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                        $payDateOldTransDate =  $oldTransDate->day($loanDate->day)->toDateString();
                        // Calculate the $newTransDate, oldTransDate difference in days
                        $diffInDaysOldTransNewTransDates = $newTransDate->diffInDays($oldTransDate);
                        //  dd($diffInDaysOldTransNewTransDates,$newTransDate,$oldTransDate);
                        //Get loan data from db
                        $loanData = Loan::where('loanID', $data->transLoanID)
                        ->get()->first();

                        $loanValue = $loanData['loanAmount'];
                        $interestRate = $loanData['loanRate'];
                        $lateFeeRate = $loanData['penaltyRate'];

                        $monthlyInterest = $loanValue * $interestRate/100;



                        if($diffInDaysOldTransNewTransDates >= 30){



                            $loanDate = Carbon::parse($this->loanDate);
                            $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                            $payDateOldTransDate =  $oldTransDate->day($loanDate->day);
                            $oldTransDate = Carbon::parse($getTransactionData->paidDate);


                            if($payDateOldTransDate > $oldTransDate){

                                if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                    $diffOldTransAndPayDateOldTransDates = $oldTransDate->diff($payDateOldTransDate);
                                    $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                    $getSmallLoan = $getSmallInterest * ($interestRate / 100);
                                    $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                    $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                    $lateFeeForSmallLoan = $diffOldTransAndPayDateOldTransDates->d * $getDailyLateFeeForSmallLoan;
                                }
                            }elseif($payDateOldTransDate == $oldTransDate){
                                //
                            }elseif($payDateOldTransDate < $oldTransDate){
                                echo "sdf";
                                if(($getTransactionData->transRestInterest ) > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                    $nextPayDateOldTransDate =  $oldTransDate->day($loanDate->day)->addMonth()->toDateString();
                                    $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                                    echo "{$nextPayDateOldTransDate}- { $oldTransDate}<br>";
                                    $diffOldTransAndNextPayDateOldTransDates = $oldTransDate->diffInDays($nextPayDateOldTransDate);
                                    echo "{$diffOldTransAndNextPayDateOldTransDates}<br>";


                                    if($oldTransDate->daysInMonth == 31){
                                        $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates - 1;
                                    }elseif($oldTransDate->daysInMonth == 28){
                                        $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates + 2;
                                    }elseif($oldTransDate->daysInMonth == 29){
                                        $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates + 1;
                                    }

                                     //dd($diffOldTransAndNextPayDateOldTransDates);
                                    $getSmallInterest = ($getTransactionData->transRestInterest) - $monthlyInterest;
                                    echo "{$getSmallInterest}<br>";

                                    $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                    echo "{$getSmallLoan}<br>";

                                    $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                    $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                    $lateFeeForSmallLoan = $diffOldTransAndNextPayDateOldTransDates * $getDailyLateFeeForSmallLoan;


                                }
                            }
                        }else{
                            $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                            if ($newTransDate->month == $oldTransDate->month){

                                if($oldTransDate < $payDateOldTransDate && $payDateOldTransDate < $newTransDate){

                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndPayDateOldTransDates = $oldTransDate->diffInDays($payDateOldTransDate);

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateOldTransDates * $getDailyLateFeeForSmallLoan;
                                    }

                                }elseif($oldTransDate < $newTransDate && $newTransDate <= $payDateOldTransDate){

                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                                    }

                                }elseif($payDateOldTransDate <= $oldTransDate && $oldTransDate < $newTransDate){


                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);
                                        // dd("htu",$diffInDaysOldTransAndNewTransDates,$oldTransDate,$newTransDate);
                                        if($newTransDate->day == 31){
                                            $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates - 1;
                                        }

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                                    }


                                }

                            }else{
                                if($oldTransDate < $payDateOldTransDate){
                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndPayDateOldTransDates = $oldTransDate->diffInDays($payDateOldTransDate);

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateOldTransDates * $getDailyLateFeeForSmallLoan;
                                    }
                                }else{
                                    if($newTransDate <= $payDateNewTransDate){
                                        if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                            $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);


                                            if($oldTransDate->daysInMonth == 31){
                                                $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates - 1;
                                            }elseif($oldTransDate->daysInMonth == 28){
                                                $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates + 2;
                                            }elseif($oldTransDate->daysInMonth == 29){
                                                $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates + 1;
                                            }

                                            $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                            $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                            $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                            $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                            $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                                        }
                                    }elseif($payDateNewTransDate < $newTransDate){
                                        if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                            $diffInDaysOldTransAndPayDateNewTransDates = $oldTransDate->diffInDays($payDateNewTransDate);

                                            if($oldTransDate->daysInMonth == 31){
                                                $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates - 1;
                                            }elseif($oldTransDate->daysInMonth == 28){
                                                $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates + 2;
                                            }elseif($oldTransDate->daysInMonth == 29){
                                                $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates + 1;
                                            }

                                            $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                            $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                            $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                            $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                            $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateNewTransDates * $getDailyLateFeeForSmallLoan;
                                        }
                                    }
                                }
                            }
                        }
                    }


                    // assign variables
                    $transPaidAmount = $data->transPaidAmount + $getTransactionData->transExtraMoney;
                    $transPaidLateFee = $data->transPaidPenaltyFee;
                    $transRestLateFee = $lateFeeForSmallLoan + $getTransactionData->transRestPenaltyFee;
                    $transPaidInterest = $data->transPaidInterest;
                    $transRestInterest = $getTransactionData->transRestInterest;
                    $transExtraMoney = 0;
                    $transReducedAmount = 0;
                    $totalInterest = 0;
                    $this->lateFeeForSmallLoan = 0;


                    // dd($lateFeeForSmallLoan);

                    //Get loan data from db
                    $loanData = Loan::where('loanID', $data->transLoanID)
                    ->get()->first();

                    $loanValue = $loanData['loanAmount'];
                    $interestRate = $loanData['loanRate'];
                    $lateFeeRate = $loanData['penaltyRate'];
                    $loanDate = $loanData['loanDate'];
                    // dd($loanDate);
                    $monthlyInterest = $loanValue * $interestRate/100;

                    if($transRestInterest < $monthlyInterest)
                    {
                        $getSmallLoan = $transRestInterest / ($interestRate / 100);
                        $monthlyLateFee = $getSmallLoan * $lateFeeRate/100;
                        $dailyLateFee = $monthlyLateFee/30;

                    }else{

                        $monthlyLateFee = $loanValue * $lateFeeRate/100;
                        $dailyLateFee = $monthlyLateFee/30;

                    }


                    $startDate = Carbon::parse($getTransactionData->paidDate);
                    $endDate = Carbon::parse($lastDate);

                    if ($startDate->year == $endDate->year && $startDate->month == $endDate->month) {

                        $loanDate = Carbon::parse($loanDate);
                        $currentMonthPayDate =  $endDate->day($loanDate->day);
                        $endDate = Carbon::parse($lastDate);
                        if($currentMonthPayDate < $endDate && $currentMonthPayDate <= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                             echo "h";
                            //  dd('o');
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }elseif($currentMonthPayDate > $endDate && $currentMonthPayDate >= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                             echo "h5";
                            //  dd('o');
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }elseif($currentMonthPayDate < $endDate && $currentMonthPayDate >= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                            //  echo "hj";
                            // dd('o');
                            $totalInterest = $monthlyInterest;
                            $startDate = $currentMonthPayDate;
                        }
                    } else {
                        //dd($lateFeeForSmallLoan);
                        $loanDate = Carbon::parse($loanDate);
                        $startDate2 = Carbon::parse($getTransactionData->paidDate);
                        $currentMonthPayDate =  $startDate2->day($loanDate->day);
                        $endDate = Carbon::parse($lastDate);
                        //  dd($currentMonthPayDate,$startDate);

                        if($currentMonthPayDate <= $startDate){

                            $nextMonthPayDate = $currentMonthPayDate->addMonth();
                            if($nextMonthPayDate >= $endDate){
                                // dd("ll");

                                $startDate = Carbon::parse($getTransactionData->paidDate);
                            }else{
                                $startDate = $nextMonthPayDate;
                            }
                    // addone month to currentmdate
                        }else{
                            $startDate = $currentMonthPayDate;
                        }


                    }


                    // dd($currentMonthPayDate,$startDate,$endDate);
                    // Calculate the difference between loan date and transaction date
                    $yearsGap = $startDate->diffInYears($endDate);
                    $monthsGap = $startDate->addYears($yearsGap)->diffInMonths($endDate);
                    $daysGap = $startDate->addMonths($monthsGap)->diffInDays($endDate);

                     //according to addingTransaction method add or minus value


                            //  dd($monthsGap,$yearsGap,$daysGap);


                    $endDateMonthPayDate =  $endDate->day($loanDate->day);
                    $endDate = Carbon::parse($lastDate);
                    $subOrAddDays = 0;
                    if ($endDateMonthPayDate >= $endDate){
                        // dd($endDateMonthPayDate,$endDate);
                        // echo "sss";
                        $givenDate = Carbon::parse($endDateMonthPayDate);
                        // Get the number of days in the previous month
                        $numberOfDaysInPreviousMonth = $givenDate->subMonthNoOverflow()->daysInMonth;

                        if ($numberOfDaysInPreviousMonth == 31){
                            echo"tttt";
                            $startDateDay = $startDate->day;
                            if($startDateDay == 31 ){
                                $subOrAddDays = 0;
                            }else{
                                $subOrAddDays = -1;
                            }



                        }
                        elseif($numberOfDaysInPreviousMonth == 28){
                            echo"oooooo";
                            $subOrAddDays = 2;

                        }
                        elseif($numberOfDaysInPreviousMonth == 29){
                             echo"iiiiii";
                             $subOrAddDays = 1;

                        }
                    }
                    else{
                        // transaction date
                        // Get the day from transaction date
                        $endDateDay = $endDate->day;
                    // dd($endDate);
                        if($endDateDay == 31){
                             echo"uuuuuu";
                             $subOrAddDays = -1;
                        }else{
                             echo"xxxx";
                             $subOrAddDays = 0;
                        }
                    }


                    $daysGap = $daysGap + $subOrAddDays;
                    // dd($monthsGap,$yearsGap,$daysGap);







                    $startDate = Carbon::parse($getTransactionData->paidDate);
                    $endDate = Carbon::parse($lastDate);

                    if ($startDate->year == $endDate->year && $startDate->month == $endDate->month) {

                        $loanDate = Carbon::parse($loanDate);
                        $currentMonthPayDate =  $endDate->day($loanDate->day);
                        $endDate = Carbon::parse($lastDate);
                        if($currentMonthPayDate < $endDate && $currentMonthPayDate <= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                                echo "h";
                            //  dd('o');
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }elseif($currentMonthPayDate > $endDate && $currentMonthPayDate >= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                                echo "h5";
                                // dd('o');
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }elseif($currentMonthPayDate < $endDate && $currentMonthPayDate >= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                            //  echo "hj";
                            // dd('o');
                            $totalInterest = $monthlyInterest;
                            $startDate = $currentMonthPayDate;
                        }
                    } else {
                        //dd($lateFeeForSmallLoan);
                        $loanDate = Carbon::parse($loanDate);
                        $startDate2 = Carbon::parse($getTransactionData->paidDate);
                        $currentMonthPayDate =  $startDate2->day($loanDate->day);
                        $endDate = Carbon::parse($lastDate);
                        //  dd($currentMonthPayDate,$startDate);

                        if($currentMonthPayDate <= $startDate){
                            $nextMonthPayDate = $currentMonthPayDate->addMonth();
                            if($nextMonthPayDate >= $endDate){
                                $startDate = Carbon::parse($getTransactionData->paidDate);
                            }else{
                                $startDate = $nextMonthPayDate;
                            }
                        }else{
                            $startDate = $currentMonthPayDate;
                        }


                    }






                    //Total Interest calculation
                    $totalInterest = $totalInterest + $monthlyInterest * 12 * $yearsGap;
                    $totalInterest = $totalInterest + $monthlyInterest * $monthsGap;
                    $totalInterest = $totalInterest + $transRestInterest;

                    // dd($lateFeeForSmallLoan);
                    if(!$lateFeeForSmallLoan){


                        $this->SecondTransCalcInterest($totalInterest);

                    }

                    //on monthly loan pay date will not add monthly interest, after that date add monthly interest value
                    if(($monthsGap <> 0 || $yearsGap <> 0) && $daysGap <> 0){
                        $totalInterest = $totalInterest + $monthlyInterest;
                        //   dd($totalInterest);
                    }else{

                    }
                    // dd($totalInterest);

                                //Total late fees calculation
                                //month diff is more than or 1, calculate late fees
                            //     $startDate = Carbon::parse($getTransactionData->paidDate);
                            //    $loanDate = Carbon::parse($loanDate);
                            //    $endDate = Carbon::parse($lastDate);
                            //     $currentMonthPayDate =  $endDate->day($loanDate->day);
                            // $startDate = Carbon::parse($getTransactionData->paidDate);
                    //   dd($currentMonthPayDate,$endDate);
                    $monthsDifference = $currentMonthPayDate->diffInMonths($endDate);


                    if($monthsDifference >= 1){

                        $totalLateFee = $monthlyLateFee * 12 * $yearsGap;
                        echo "$monthlyLateFee";
                        $totalLateFee = $totalLateFee + $monthlyLateFee * $monthsGap;
                        echo "$totalLateFee";
                        $totalLateFee = $totalLateFee + $dailyLateFee * $daysGap;
                        echo "$totalLateFee";


                        $totalLateFee = $totalLateFee + $transRestLateFee;

                    }elseif($monthsDifference == 0){

                        if($totalInterest > $monthlyInterest && $totalInterest < (2  * $monthlyInterest)){
                            $totalLateFee = $transRestLateFee +  $this->lateFeeForSmallLoan;

                        }else{
                            // dd('hio',$transRestLateFee);
                            if(!$lateFeeForSmallLoan){
                                //  dd('hio',$transRestLateFee);
                                if($totalInterest >= 2  * $monthlyInterest){
                                    $totalLateFee = $transRestLateFee + ($dailyLateFee * $daysGap);
                                    // dd($totalLateFee);
                                }else{
                                    $totalLateFee = $transRestLateFee;
                                    // dd('hio',$transRestLateFee);
                                }

                            }else{
                                // dd('hio',$transRestLateFee);
                                $totalLateFee = $transRestLateFee;
                            }

                            // + $this->lateFeeForSmallLoan ;
                    // dd('ghg',$monthsDifference,$totalLateFee);
                        }

                        // dd( $totalLateFee);
                    // dd($transRestLateFee,$totalLateFee,$this->lateFeeForSmallLoan);
                    }

                    //Add late fees to DB $transPaidLateFee - $transRestLateFee
                    if($transPaidAmount >= $totalLateFee){

                        $transPaidLateFee = $totalLateFee;
                        $transRestLateFee = 0;
                        $transPaidAmount = $transPaidAmount - $totalLateFee;

                    }
                    else{

                        $transPaidLateFee = $transPaidAmount;
                        $transRestLateFee = $totalLateFee - $transPaidAmount;
                        $transPaidAmount = 0;

                    }


                    //Add interest to DB
                    if($transPaidAmount >= $totalInterest){

                        $transPaidInterest = $totalInterest;
                        $transRestInterest = 0;
                        $transPaidAmount= $transPaidAmount - $totalInterest;
                    }
                    else{

                        $transPaidInterest = $transPaidAmount;
                        $transRestInterest = $totalInterest - $transPaidAmount;
                        $transPaidAmount = 0;

                    }


                    //Add extra money or reduce from loan
                    if($data->extraMoney == "keep" && $transPaidAmount > 0 ){
                        $transExtraMoney = $transPaidAmount;
                    }
                    elseif($data->extraMoney == "reduce" && $transPaidAmount > 0 ) {
                        $transReducedAmount = $transPaidAmount;

                        // update loan value by reducing
                        Loan::where('loanID', $data->transLoanID)
                        ->decrement('loanAmount', $transReducedAmount);

                    }
                    // dd("paidamount  $data->transPaidAmount","latFeesForSmallInt $lateFeeForSmallLoan" ,"RestLateFee $transRestLateFee","RestInterest $transRestInterest","Extram $transExtraMoney","Paid Latefee $transPaidLateFee");

                    // Create a new instance of the Transaction model
                    $storeToTransaction = new Transaction();

                    // Set the values for each column based on your data
                    $storeToTransaction->paidDate = $lastDate;
                    $storeToTransaction->transDetails = $data->transDetails;
                    $storeToTransaction->transPaidAmount = $data->transPaidAmount;
                    $storeToTransaction->transAllPaid =  $data->transPaidAmount + $getTransactionData->transAllPaid;
                    $storeToTransaction->transPaidInterest = $transPaidInterest;
                    $storeToTransaction->transPaidPenaltyFee = $transPaidLateFee;
                    $storeToTransaction->transRestInterest = $transRestInterest;
                    $storeToTransaction->transRestPenaltyFee = $transRestLateFee;
                    $storeToTransaction->transReducedAmount = $transReducedAmount;
                    $storeToTransaction->transExtraMoney = $transExtraMoney;
                    $storeToTransaction->transLoanID = $data->transLoanID;


                    // Save the model to the database
                    $storeToTransaction->save();


                    ///
                    //////
                    //////
                    //////

                    $loanDate = Carbon::parse($this->loanDate);
                    $newTransDate = Carbon::parse($data->paidDate);
                    // echo "{$startDate} - {$endDate}<br><br>";
                    $payDateNewTransDate =  $newTransDate->day($loanDate->day)->toDateString();
                    $lateFeeForSmallLoan = 0;
                    if($payDateNewTransDate > $loanDate){
                        $newTransDate = Carbon::parse($data->paidDate);
                        $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                        $payDateOldTransDate =  $oldTransDate->day($loanDate->day)->toDateString();
                        // Calculate the $newTransDate, oldTransDate difference in days
                        $diffInDaysOldTransNewTransDates = $newTransDate->diffInDays($oldTransDate);
                        //  dd($diffInDaysOldTransNewTransDates,$newTransDate,$oldTransDate);
                        //Get loan data from db
                        $loanData = Loan::where('loanID', $data->transLoanID)
                        ->get()->first();

                        $loanValue = $loanData['loanAmount'];
                        $interestRate = $loanData['loanRate'];
                        $lateFeeRate = $loanData['penaltyRate'];

                        $monthlyInterest = $loanValue * $interestRate/100;



                        if($diffInDaysOldTransNewTransDates >= 30){



                            $loanDate = Carbon::parse($this->loanDate);
                            $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                            $payDateOldTransDate =  $oldTransDate->day($loanDate->day);
                            $oldTransDate = Carbon::parse($getTransactionData->paidDate);


                            if($payDateOldTransDate > $oldTransDate){

                                if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                    $diffOldTransAndPayDateOldTransDates = $oldTransDate->diff($payDateOldTransDate);
                                    $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                    $getSmallLoan = $getSmallInterest * ($interestRate / 100);
                                    $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                    $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                    $lateFeeForSmallLoan = $diffOldTransAndPayDateOldTransDates->d * $getDailyLateFeeForSmallLoan;
                                }
                            }elseif($payDateOldTransDate == $oldTransDate){
                                //
                            }elseif($payDateOldTransDate < $oldTransDate){
                                echo "sdf";
                                if(($getTransactionData->transRestInterest ) > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                    $nextPayDateOldTransDate =  $oldTransDate->day($loanDate->day)->addMonth()->toDateString();
                                    $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                                    echo "{$nextPayDateOldTransDate}- { $oldTransDate}<br>";
                                    $diffOldTransAndNextPayDateOldTransDates = $oldTransDate->diffInDays($nextPayDateOldTransDate);
                                    echo "{$diffOldTransAndNextPayDateOldTransDates}<br>";


                                    if($oldTransDate->daysInMonth == 31){
                                        $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates - 1;
                                    }elseif($oldTransDate->daysInMonth == 28){
                                        $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates + 2;
                                    }elseif($oldTransDate->daysInMonth == 29){
                                        $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates + 1;
                                    }

                                     //dd($diffOldTransAndNextPayDateOldTransDates);
                                    $getSmallInterest = ($getTransactionData->transRestInterest) - $monthlyInterest;
                                    echo "{$getSmallInterest}<br>";

                                    $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                    echo "{$getSmallLoan}<br>";

                                    $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                    $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                    $lateFeeForSmallLoan = $diffOldTransAndNextPayDateOldTransDates * $getDailyLateFeeForSmallLoan;


                                }
                            }
                        }else{
                            $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                            if ($newTransDate->month == $oldTransDate->month){

                                if($oldTransDate < $payDateOldTransDate && $payDateOldTransDate < $newTransDate){

                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndPayDateOldTransDates = $oldTransDate->diffInDays($payDateOldTransDate);

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateOldTransDates * $getDailyLateFeeForSmallLoan;
                                    }

                                }elseif($oldTransDate < $newTransDate && $newTransDate <= $payDateOldTransDate){

                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                                    }

                                }elseif($payDateOldTransDate <= $oldTransDate && $oldTransDate < $newTransDate){


                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);
                                        // dd("htu",$diffInDaysOldTransAndNewTransDates,$oldTransDate,$newTransDate);
                                        if($newTransDate->day == 31){
                                            $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates - 1;
                                        }

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                                    }


                                }

                            }else{
                                if($oldTransDate < $payDateOldTransDate){
                                    if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                        $diffInDaysOldTransAndPayDateOldTransDates = $oldTransDate->diffInDays($payDateOldTransDate);

                                        $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                        $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateOldTransDates * $getDailyLateFeeForSmallLoan;
                                    }
                                }else{
                                    if($newTransDate <= $payDateNewTransDate){
                                        if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                            $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);


                                            if($oldTransDate->daysInMonth == 31){
                                                $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates - 1;
                                            }elseif($oldTransDate->daysInMonth == 28){
                                                $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates + 2;
                                            }elseif($oldTransDate->daysInMonth == 29){
                                                $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates + 1;
                                            }

                                            $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                            $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                            $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                            $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                            $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                                        }
                                    }elseif($payDateNewTransDate < $newTransDate){
                                        if($getTransactionData->transRestInterest > $monthlyInterest && $getTransactionData->transRestInterest < (2  * $monthlyInterest)){

                                            $diffInDaysOldTransAndPayDateNewTransDates = $oldTransDate->diffInDays($payDateNewTransDate);

                                            if($oldTransDate->daysInMonth == 31){
                                                $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates - 1;
                                            }elseif($oldTransDate->daysInMonth == 28){
                                                $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates + 2;
                                            }elseif($oldTransDate->daysInMonth == 29){
                                                $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates + 1;
                                            }

                                            $getSmallInterest = $getTransactionData->transRestInterest - $monthlyInterest;

                                            $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                            $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                            $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                            $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateNewTransDates * $getDailyLateFeeForSmallLoan;
                                        }
                                    }
                                }
                            }
                        }
                    }


                    // assign variables
                    $transPaidAmount = $data->transPaidAmount + $getTransactionData->transExtraMoney;
                    $transPaidLateFee = $data->transPaidPenaltyFee;
                    $transRestLateFee = $lateFeeForSmallLoan + $getTransactionData->transRestPenaltyFee;
                    $transPaidInterest = $data->transPaidInterest;
                    $transRestInterest = $getTransactionData->transRestInterest;
                    $transExtraMoney = 0;
                    $transReducedAmount = 0;
                    $totalInterest = 0;
                    $this->lateFeeForSmallLoan = 0;


                    // dd($lateFeeForSmallLoan);

                    //Get loan data from db
                    $loanData = Loan::where('loanID', $data->transLoanID)
                    ->get()->first();

                    $loanValue = $loanData['loanAmount'];
                    $interestRate = $loanData['loanRate'];
                    $lateFeeRate = $loanData['penaltyRate'];
                    $loanDate = $loanData['loanDate'];
                    // dd($loanDate);
                    $monthlyInterest = $loanValue * $interestRate/100;

                    if($transRestInterest < $monthlyInterest)
                    {
                        $getSmallLoan = $transRestInterest / ($interestRate / 100);
                        $monthlyLateFee = $getSmallLoan * $lateFeeRate/100;
                        $dailyLateFee = $monthlyLateFee/30;

                    }else{

                        $monthlyLateFee = $loanValue * $lateFeeRate/100;
                        $dailyLateFee = $monthlyLateFee/30;

                    }


                    $startDate = Carbon::parse($getTransactionData->paidDate);
                    $endDate = Carbon::parse($data->paidDate);

                    if ($startDate->year == $endDate->year && $startDate->month == $endDate->month) {

                        $loanDate = Carbon::parse($loanDate);
                        $currentMonthPayDate =  $endDate->day($loanDate->day);
                        $endDate = Carbon::parse($data->paidDate);
                        if($currentMonthPayDate < $endDate && $currentMonthPayDate <= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                             echo "h";
                            //  dd('o');
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }elseif($currentMonthPayDate > $endDate && $currentMonthPayDate >= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                             echo "h5";
                            //  dd('o');
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }elseif($currentMonthPayDate < $endDate && $currentMonthPayDate >= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                            //  echo "hj";
                            // dd('o');
                            $totalInterest = $monthlyInterest;
                            $startDate = $currentMonthPayDate;
                        }
                    } else {
                        //dd($lateFeeForSmallLoan);
                        $loanDate = Carbon::parse($loanDate);
                        $startDate2 = Carbon::parse($getTransactionData->paidDate);
                        $currentMonthPayDate =  $startDate2->day($loanDate->day);
                        $endDate = Carbon::parse($data->paidDate);
                        //  dd($currentMonthPayDate,$startDate);

                        if($currentMonthPayDate <= $startDate){

                            $nextMonthPayDate = $currentMonthPayDate->addMonth();
                            if($nextMonthPayDate >= $endDate){
                                // dd("ll");

                                $startDate = Carbon::parse($getTransactionData->paidDate);
                            }else{
                                $startDate = $nextMonthPayDate;
                            }
                    // addone month to currentmdate
                        }else{
                            $startDate = $currentMonthPayDate;
                        }


                    }


                    // dd($currentMonthPayDate,$startDate,$endDate);
                    // Calculate the difference between loan date and transaction date
                    $yearsGap = $startDate->diffInYears($endDate);
                    $monthsGap = $startDate->addYears($yearsGap)->diffInMonths($endDate);
                    $daysGap = $startDate->addMonths($monthsGap)->diffInDays($endDate);

                     //according to addingTransaction method add or minus value


                            //  dd($monthsGap,$yearsGap,$daysGap);


                    $endDateMonthPayDate =  $endDate->day($loanDate->day);
                    $endDate = Carbon::parse($data->paidDate);
                    $subOrAddDays = 0;
                    if ($endDateMonthPayDate >= $endDate){
                        // dd($endDateMonthPayDate,$endDate);
                        // echo "sss";
                        $givenDate = Carbon::parse($endDateMonthPayDate);
                        // Get the number of days in the previous month
                        $numberOfDaysInPreviousMonth = $givenDate->subMonthNoOverflow()->daysInMonth;

                        if ($numberOfDaysInPreviousMonth == 31){
                            echo"tttt";
                            $startDateDay = $startDate->day;
                            if($startDateDay == 31 ){
                                $subOrAddDays = 0;
                            }else{
                                $subOrAddDays = -1;
                            }



                        }
                        elseif($numberOfDaysInPreviousMonth == 28){
                            echo"oooooo";
                            $subOrAddDays = 2;

                        }
                        elseif($numberOfDaysInPreviousMonth == 29){
                             echo"iiiiii";
                             $subOrAddDays = 1;

                        }
                    }
                    else{
                        // transaction date
                        // Get the day from transaction date
                        $endDateDay = $endDate->day;
                    // dd($endDate);
                        if($endDateDay == 31){
                             echo"uuuuuu";
                             $subOrAddDays = -1;
                        }else{
                             echo"xxxx";
                             $subOrAddDays = 0;
                        }
                    }


                    $daysGap = $daysGap + $subOrAddDays;
                    // dd($monthsGap,$yearsGap,$daysGap);







                    $startDate = Carbon::parse($getTransactionData->paidDate);
                    $endDate = Carbon::parse($data->paidDate);

                    if ($startDate->year == $endDate->year && $startDate->month == $endDate->month) {

                        $loanDate = Carbon::parse($loanDate);
                        $currentMonthPayDate =  $endDate->day($loanDate->day);
                        $endDate = Carbon::parse($data->paidDate);
                        if($currentMonthPayDate < $endDate && $currentMonthPayDate <= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                                echo "h";
                            //  dd('o');
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }elseif($currentMonthPayDate > $endDate && $currentMonthPayDate >= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                                echo "h5";
                                // dd('o');
                            $startDate = Carbon::parse($getTransactionData->paidDate);
                        }elseif($currentMonthPayDate < $endDate && $currentMonthPayDate >= $startDate){
                            if($currentMonthPayDate == $startDate){
                                $totalInterest = $monthlyInterest;
                            }
                            //  echo "hj";
                            // dd('o');
                            $totalInterest = $monthlyInterest;
                            $startDate = $currentMonthPayDate;
                        }
                    } else {
                        //dd($lateFeeForSmallLoan);
                        $loanDate = Carbon::parse($loanDate);
                        $startDate2 = Carbon::parse($getTransactionData->paidDate);
                        $currentMonthPayDate =  $startDate2->day($loanDate->day);
                        $endDate = Carbon::parse($data->paidDate);
                        //  dd($currentMonthPayDate,$startDate);

                        if($currentMonthPayDate <= $startDate){
                            $nextMonthPayDate = $currentMonthPayDate->addMonth();
                            if($nextMonthPayDate >= $endDate){
                                $startDate = Carbon::parse($getTransactionData->paidDate);
                            }else{
                                $startDate = $nextMonthPayDate;
                            }
                        }else{
                            $startDate = $currentMonthPayDate;
                        }


                    }






                    //Total Interest calculation
                    $totalInterest = $totalInterest + $monthlyInterest * 12 * $yearsGap;
                    $totalInterest = $totalInterest + $monthlyInterest * $monthsGap;
                    $totalInterest = $totalInterest + $transRestInterest;

                    // dd($lateFeeForSmallLoan);
                    if(!$lateFeeForSmallLoan){


                        $this->SecondTransCalcInterest($totalInterest);

                    }

                    //on monthly loan pay date will not add monthly interest, after that date add monthly interest value
                    if(($monthsGap <> 0 || $yearsGap <> 0) && $daysGap <> 0){
                        $totalInterest = $totalInterest + $monthlyInterest;
                        //   dd($totalInterest);
                    }else{

                    }
                    // dd($totalInterest);

                                //Total late fees calculation
                                //month diff is more than or 1, calculate late fees
                            //     $startDate = Carbon::parse($getTransactionData->paidDate);
                            //    $loanDate = Carbon::parse($loanDate);
                            //    $endDate = Carbon::parse($data->paidDate);
                            //     $currentMonthPayDate =  $endDate->day($loanDate->day);
                            // $startDate = Carbon::parse($getTransactionData->paidDate);
                    //   dd($currentMonthPayDate,$endDate);
                    $monthsDifference = $currentMonthPayDate->diffInMonths($endDate);


                    if($monthsDifference >= 1){

                        $totalLateFee = $monthlyLateFee * 12 * $yearsGap;
                        echo "$monthlyLateFee";
                        $totalLateFee = $totalLateFee + $monthlyLateFee * $monthsGap;
                        echo "$totalLateFee";
                        $totalLateFee = $totalLateFee + $dailyLateFee * $daysGap;
                        echo "$totalLateFee";


                        $totalLateFee = $totalLateFee + $transRestLateFee;

                    }elseif($monthsDifference == 0){

                        if($totalInterest > $monthlyInterest && $totalInterest < (2  * $monthlyInterest)){
                            $totalLateFee = $transRestLateFee +  $this->lateFeeForSmallLoan;

                        }else{
                            // dd('hio',$transRestLateFee);
                            if(!$lateFeeForSmallLoan){
                                //  dd('hio',$transRestLateFee);
                                if($totalInterest >= 2  * $monthlyInterest){
                                    $totalLateFee = $transRestLateFee + ($dailyLateFee * $daysGap);
                                    // dd($totalLateFee);
                                }else{
                                    $totalLateFee = $transRestLateFee;
                                    // dd('hio',$transRestLateFee);
                                }

                            }else{
                                // dd('hio',$transRestLateFee);
                                $totalLateFee = $transRestLateFee;
                            }

                            // + $this->lateFeeForSmallLoan ;
                    // dd('ghg',$monthsDifference,$totalLateFee);
                        }

                        // dd( $totalLateFee);
                    // dd($transRestLateFee,$totalLateFee,$this->lateFeeForSmallLoan);
                    }

                    //Add late fees to DB $transPaidLateFee - $transRestLateFee
                    if($transPaidAmount >= $totalLateFee){

                        $transPaidLateFee = $totalLateFee;
                        $transRestLateFee = 0;
                        $transPaidAmount = $transPaidAmount - $totalLateFee;

                    }
                    else{

                        $transPaidLateFee = $transPaidAmount;
                        $transRestLateFee = $totalLateFee - $transPaidAmount;
                        $transPaidAmount = 0;

                    }


                    //Add interest to DB
                    if($transPaidAmount >= $totalInterest){

                        $transPaidInterest = $totalInterest;
                        $transRestInterest = 0;
                        $transPaidAmount= $transPaidAmount - $totalInterest;
                    }
                    else{

                        $transPaidInterest = $transPaidAmount;
                        $transRestInterest = $totalInterest - $transPaidAmount;
                        $transPaidAmount = 0;

                    }


                    //Add extra money or reduce from loan
                    if($data->extraMoney == "keep" && $transPaidAmount > 0 ){
                        $transExtraMoney = $transPaidAmount;
                    }
                    elseif($data->extraMoney == "reduce" && $transPaidAmount > 0 ) {
                        $transReducedAmount = $transPaidAmount;

                        // update loan value by reducing
                        Loan::where('loanID', $data->transLoanID)
                        ->decrement('loanAmount', $transReducedAmount);

                    }
                    // dd("paidamount  $data->transPaidAmount","latFeesForSmallInt $lateFeeForSmallLoan" ,"RestLateFee $transRestLateFee","RestInterest $transRestInterest","Extram $transExtraMoney","Paid Latefee $transPaidLateFee");

                    // Create a new instance of the Transaction model
                    $storeToTransaction = new Transaction();

                    // Set the values for each column based on your data
                    $storeToTransaction->paidDate = $data->paidDate;
                    $storeToTransaction->transDetails = $data->transDetails;
                    $storeToTransaction->transPaidAmount = $data->transPaidAmount;
                    $storeToTransaction->transAllPaid =  $data->transPaidAmount + $getTransactionData->transAllPaid;
                    $storeToTransaction->transPaidInterest = $transPaidInterest;
                    $storeToTransaction->transPaidPenaltyFee = $transPaidLateFee;
                    $storeToTransaction->transRestInterest = $transRestInterest;
                    $storeToTransaction->transRestPenaltyFee = $transRestLateFee;
                    $storeToTransaction->transReducedAmount = $transReducedAmount;
                    $storeToTransaction->transExtraMoney = $transExtraMoney;
                    $storeToTransaction->transLoanID = $data->transLoanID;


                    // Save the model to the database
                    $storeToTransaction->save();

                }



            }







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
                     echo"tttt";
                    $this->calcInterest(-1);

                }
                elseif($numberOfDaysInPreviousMonth == 28){
                    echo"oooooo";
                    $this->calcInterest(+2);

                }
                elseif($numberOfDaysInPreviousMonth == 29){
                     echo"iiiiii";
                    $this->calcInterest(+1);

                }
            }
            else{
                // transaction date
                $transPayDate = Carbon::parse($data->paidDate);
                // Get the day from transaction date
                $transPayDay = $transPayDate->day;

                if($transPayDay == 31){
                     echo"uuuuuu";
                    $this->calcInterest(-1);
                }else{
                     echo"xxxx";
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


        //  $diff = $startDate->diff($endDate);
        // // $diff = date_diff($startDate,$endDate);
        // $daysGap = $diff->d + $changingDayDiff;//according to addingTransaction method add or minus value
        // $yearsGap = $diff->y;
        // $monthsGap =(int)$startDate->floatDiffInMonths($endDate);


        //Calculate the difference between loan date and transaction date
         $yearsGap = $startDate->diffInYears($endDate);
         $monthsGap = $startDate->addYears($yearsGap)->diffInMonths($endDate);
         $daysGap = $startDate->addMonths($monthsGap)->diffInDays($endDate) + $changingDayDiff; //according to addingTransaction method add or minus value


        // dd($changingDayDiff,$daysGap,$monthsGap,$yearsGap);

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
        $startDate = Carbon::parse($this->loanDate);
        $endDate = Carbon::parse($requestData->paidDate);
        $monthsDifference = $startDate->diffInMonths($endDate);
        // dd($startDate,$monthsDifference,$endDate);
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

    private function SecondTransCalcInterest($totalInterest){

        //get request data from main method
        $requestData = $this->requestData;
        $this->lateFeeForSmallLoan = 0;
        $getTransactionData = Transaction::where('transLoanID',$requestData->transLoanID)
        ->where('transStatus', 0)
        ->orderBy('transID', 'desc')->first();

        $loanDate = Carbon::parse($this->loanDate);
        $newTransDate = Carbon::parse($requestData->paidDate);
        // echo "{$startDate} - {$endDate}<br><br>";
        $payDateNewTransDate =  $newTransDate->day($loanDate->day)->toDateString();
        $lateFeeForSmallLoan = 0;
        if($payDateNewTransDate > $loanDate){
            $newTransDate = Carbon::parse($requestData->paidDate);
            $oldTransDate = Carbon::parse($getTransactionData->paidDate);
            $payDateOldTransDate =  $oldTransDate->day($loanDate->day);
            // Calculate the $newTransDate, oldTransDate difference in days
            $diffInDaysOldTransNewTransDates = $newTransDate->diffInDays($oldTransDate);
            //  dd($diffInDaysOldTransNewTransDates,$newTransDate,$oldTransDate);
            //Get loan data from db
            $loanData = Loan::where('loanID', $requestData->transLoanID)
            ->get()->first();

            $loanValue = $loanData['loanAmount'];
            $interestRate = $loanData['loanRate'];
            $lateFeeRate = $loanData['penaltyRate'];

            $monthlyInterest = $loanValue * $interestRate/100;



            if($diffInDaysOldTransNewTransDates >= 30){



                $loanDate = Carbon::parse($this->loanDate);
                $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                $payDateOldTransDate =  $oldTransDate->day($loanDate->day)->toDateString();
                $oldTransDate = Carbon::parse($getTransactionData->paidDate);


                if($payDateOldTransDate > $oldTransDate){

                    if($totalInterest > $monthlyInterest && $totalInterest < (2  * $monthlyInterest)){

                        $diffOldTransAndPayDateOldTransDates = $oldTransDate->diff($payDateOldTransDate);
                        $getSmallInterest = $totalInterest - $monthlyInterest;

                        $getSmallLoan = $getSmallInterest * ($interestRate / 100);
                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                        $lateFeeForSmallLoan = $diffOldTransAndPayDateOldTransDates->d * $getDailyLateFeeForSmallLoan;
                    }
                }elseif($payDateOldTransDate == $oldTransDate){
                    //
                }elseif($payDateOldTransDate < $oldTransDate){
                    echo "sdf";
                    if(($totalInterest ) > $monthlyInterest && $totalInterest < (2  * $monthlyInterest)){

                        $nextPayDateOldTransDate =  $oldTransDate->day($loanDate->day)->addMonth()->toDateString();
                        $oldTransDate = Carbon::parse($getTransactionData->paidDate);
                        echo "{$nextPayDateOldTransDate}- { $oldTransDate}<br>";
                        $diffOldTransAndNextPayDateOldTransDates = $oldTransDate->diffInDays($nextPayDateOldTransDate);
                        echo "{$diffOldTransAndNextPayDateOldTransDates}<br>";


                        if($oldTransDate->daysInMonth == 31){
                            $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates - 1;
                        }elseif($oldTransDate->daysInMonth == 28){
                            $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates + 2;
                        }elseif($oldTransDate->daysInMonth == 29){
                            $diffOldTransAndNextPayDateOldTransDates = $diffOldTransAndNextPayDateOldTransDates + 1;
                        }

                            //dd($diffOldTransAndNextPayDateOldTransDates);
                        $getSmallInterest = ($totalInterest) - $monthlyInterest;
                        echo "{$getSmallInterest}<br>";

                        $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                        echo "{$getSmallLoan}<br>";

                        $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                        $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                        $lateFeeForSmallLoan = $diffOldTransAndNextPayDateOldTransDates * $getDailyLateFeeForSmallLoan;


                    }
                }
            }else{
                if ($newTransDate->month == $oldTransDate->month){
                    // if($oldTransDate <> $payDateOldTransDate){
                    //    dd($oldTransDate,$payDateOldTransDate,$newTransDate);
                    // }


                    if($oldTransDate <= $payDateOldTransDate && $payDateOldTransDate < $newTransDate){

                        if($totalInterest > $monthlyInterest && $totalInterest < (2  * $monthlyInterest)){
echo " 1gg";
                            $diffInDaysOldTransAndPayDateOldTransDates = $newTransDate->diffInDays($payDateNewTransDate);

                            $getSmallInterest = $totalInterest - $monthlyInterest;

                            $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                            $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                            $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                            $this->lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateOldTransDates * $getDailyLateFeeForSmallLoan;
                           // dd($oldTransDate,$payDateOldTransDate,$newTransDate,$totalInterest,$lateFeeForSmallLoan);
                        }

                    }elseif($oldTransDate < $newTransDate && $newTransDate <= $payDateOldTransDate){
                        echo " 2gg";
                        if($totalInterest > $monthlyInterest && $totalInterest < (2  * $monthlyInterest)){

                            $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);

                            $getSmallInterest = $totalInterest - $monthlyInterest;

                            $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                            $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                            $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                            $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                        }

                    }elseif($payDateOldTransDate <= $oldTransDate && $oldTransDate < $newTransDate){

                        echo " 3gg";
                        if($totalInterest > $monthlyInterest && $totalInterest < (2  * $monthlyInterest)){

                            $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);

                            if($newTransDate->day == 31){
                                $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates - 1;
                            }

                            $getSmallInterest = $totalInterest - $monthlyInterest;

                            $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                            $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                            $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                            $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                        }


                    }
                    //  dd("jl");
                }else{
                    if($oldTransDate < $payDateOldTransDate){
                        if($totalInterest > $monthlyInterest && $totalInterest < (2  * $monthlyInterest)){

                            $diffInDaysOldTransAndPayDateOldTransDates = $oldTransDate->diffInDays($payDateOldTransDate);

                            $getSmallInterest = $totalInterest - $monthlyInterest;

                            $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                            $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                            $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                            $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateOldTransDates * $getDailyLateFeeForSmallLoan;
                        }
                    }else{
                        if($newTransDate <= $payDateNewTransDate){
                            if($totalInterest > $monthlyInterest && $totalInterest < (2  * $monthlyInterest)){

                                $diffInDaysOldTransAndNewTransDates = $oldTransDate->diffInDays($newTransDate);


                                if($oldTransDate->daysInMonth == 31){
                                    $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates - 1;
                                }elseif($oldTransDate->daysInMonth == 28){
                                    $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates + 2;
                                }elseif($oldTransDate->daysInMonth == 29){
                                    $diffInDaysOldTransAndNewTransDates = $diffInDaysOldTransAndNewTransDates + 1;
                                }

                                $getSmallInterest = $totalInterest - $monthlyInterest;

                                $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                $lateFeeForSmallLoan = $diffInDaysOldTransAndNewTransDates * $getDailyLateFeeForSmallLoan;
                            }
                        }elseif($payDateNewTransDate < $newTransDate){
                            if($totalInterest > $monthlyInterest && $totalInterest < (2  * $monthlyInterest)){

                                $diffInDaysOldTransAndPayDateNewTransDates = $oldTransDate->diffInDays($payDateNewTransDate);

                                if($oldTransDate->daysInMonth == 31){
                                    $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates - 1;
                                }elseif($oldTransDate->daysInMonth == 28){
                                    $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates + 2;
                                }elseif($oldTransDate->daysInMonth == 29){
                                    $diffInDaysOldTransAndPayDateNewTransDates = $diffInDaysOldTransAndPayDateNewTransDates + 1;
                                }

                                $getSmallInterest = $totalInterest - $monthlyInterest;

                                $getSmallLoan = $getSmallInterest / ($interestRate / 100);
                                $getMonthlyLateFeeForSmallLoan = $getSmallLoan * ($lateFeeRate / 100);

                                $getDailyLateFeeForSmallLoan = $getMonthlyLateFeeForSmallLoan / 30;

                                $lateFeeForSmallLoan = $diffInDaysOldTransAndPayDateNewTransDates * $getDailyLateFeeForSmallLoan;
                            }
                        }
                    }
                }
            }
        }



    }

    private function SecondTransStartDate($totalInterest){
        // $loanData = Loan::where('loanID', $data->transLoanID)
        // ->get()->first();
        // $loanDate = $loanData['loanDate'];

        // $this->requestData;
        // $this->getTransactionData;


        //I duplicated it//
    }




}