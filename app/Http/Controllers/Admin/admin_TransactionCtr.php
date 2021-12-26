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
        ->where('loans.loanID',$loanID)->get(['users.name','users.NIC','loans.loanID','transactions.*']);
        return view('Users.Admin.Transactions.viewTransaction',compact('transactionData'));

    }

    public function deleteTransaction($transID)
    {
        //dd($branchID);
        $delete = Transaction::find($transID);
        $delete->delete();
        //return redirect()->back()->with('message','successful');
        return redirect()->route('admin.allTransaction');
    }

    public function addingTransaction(Request $data)
    {
        $data->validate([

            'paidDate' => ['required'],
            'transPaidAmount' => ['required','max:99999999','numeric'],
            'transLoanID' => ['required']
 
        ]);

        //dd($data->extraMoney);

        $getTransactionData = Transaction::where('transLoanID',$data->transLoanID)
        ->orderBy('transID', 'desc')->first();

        $loanData = Loan::where('loanID', $data->transLoanID)
        ->get()->first();

        $getDate = new DateTime();
        $newDate = $getDate->format('Y-m-d');

        if ($getTransactionData == Null){
            
            //$user = Transaction::create($data->all());
            


            $generatedPenaltyFee = 0;
            $penaltyDays = 0;
            $gotLoanDate = new DateTime($loanData->loanDate);
            $gotPaidDate = new DateTime($data->paidDate);
            $interval = $gotLoanDate->diff($gotPaidDate);
            $days = $interval->format('%a');//now do whatever you like with $days
            
            //dd($interval->m,$interval->d);
            
            $moreDays = $interval->d;
            $moreMonths = $interval->m;
            $moreYears = $interval->y;
            
            //dd($moreMonths);

            if ($moreMonths > 0 && $moreDays > 0 && $moreYears > 0) {
                $calAllInterest = (($loanData->loanAmount * ($loanData->loanRate/100)) * ($moreMonths + ($moreYears * 12)));
                
            }

            if ($moreMonths == 0 && $moreDays > 0 && $moreYears > 0) {
                $calAllInterest = ($loanData->loanAmount * ($loanData->loanRate/100)) *  (1 + ($moreYears * 12));
            }

            if ($moreMonths > 0 && $moreDays == 0 && $moreYears > 0) {
                $calAllInterest = ($loanData->loanAmount * ($loanData->loanRate/100)) * ($moreMonths + ($moreYears * 12));
            }

            if ($moreMonths == 0 && $moreDays == 0 && $moreYears > 0) {
                $calAllInterest = (($loanData->loanAmount * ($loanData->loanRate/100)) * ($moreYears * 12));
            }

            if ($moreMonths == 0 && $moreDays == 0 && $moreYears == 0) {
                $calAllInterest = 0;
            }

            if ($moreMonths > 0 && $moreDays > 0 && $moreYears == 0) {
                $calAllInterest = (($loanData->loanAmount * ($loanData->loanRate/100)) * ($moreMonths ));
            }

            if ($moreMonths == 0 && $moreDays > 0 && $moreYears == 0) {
                $calAllInterest = (($loanData->loanAmount * ($loanData->loanRate/100)) * 1);
                
            }

            if ($moreMonths > 0 && $moreDays == 0 && $moreYears == 0) {
                $calAllInterest = (($loanData->loanAmount * ($loanData->loanRate/100)) * $moreMonths );
            }


            $newData = new Transaction();
            $newData->paidDate = $data->paidDate;
            $newData->transPaidAmount = $data->transPaidAmount;
            $newData->transLoanID = $data->transLoanID;
            $newData->transDetails = $data->transDetails;
            $newData->transAllPaid = $data->transPaidAmount;


            
            //current Loan Paying month and year
            $paidDateToGetTheMonth = Carbon::createFromFormat('Y-m-d', $data->paidDate);
            $monthName = $paidDateToGetTheMonth->format('m');
            $year = $paidDateToGetTheMonth->format('Y');
            
            //get Due date from loan table
            $date = Carbon::createFromFormat('Y-m-d', $loanData->loanDate);
            $dueDate = $date->format('d');



             ////////////////////////////////////////////////////////////////////////
            //get Due date from loan table
            $date = Carbon::createFromFormat('Y-m-d', $loanData->loanDate);
            $dueDay = $date->format('j');

            //get Current month and year
            $date = Carbon::createFromFormat('Y-m-d', $newDate);
            //month
            $dueMonth = $date->format('n');
            //year
            $dueYear = $date->format('Y');

            //create date according to current month year and Due date 
            $createdDate = Carbon::createFromDate($dueYear, $dueMonth, $dueDay)->toDateString();

            $CurrentMonthDueDate = Carbon::createFromFormat('Y-m-d', $createdDate);
            $newDate2 = Carbon::createFromFormat('Y-m-d', $data->paidDate);

            //get different amount of Months
            $diff_in_months = $CurrentMonthDueDate->diffInMonths($loanData->loanDate);
            
            $diff_in_months2 = $newDate2->diffInMonths($loanData->loanDate);

            ////////////////////////////////////////////////////////////////////

            

            if ($diff_in_months >=1 && $diff_in_months2 > 0){
                

                if ($monthName == 1 || $monthName == 3 || $monthName == 5 || $monthName == 7 || $monthName == 8 || $monthName == 10 || $monthName == 12) {
                    
                    $extraDays = 31 - $dueDate;

                    if ($moreDays >= $extraDays ) {

                        $penaltyDays = $moreDays-1;
                        
                    }else{
                        $penaltyDays = $moreDays;
                    }

                }elseif ($monthName == 2) {
                    if ($year / 4 ==0) {
                        $extraDays = 29 - $dueDate;

                        if ($moreDays >= $extraDays ) {
                            
                            $penaltyDays = $moreDays+1;
                            
                        }else{
                            $penaltyDays = $moreDays;
                        }
                    }else{
                        $extraDays = 28 - $dueDate;

                        if ($moreDays >= $extraDays ) {

                            $penaltyDays = $moreDays+2;
                            
                        }else{
                            $penaltyDays = $moreDays;
                        }
                    }
                }else {

                    //If there is no extra days
                    $penaltyDays = $moreDays;

                }
            }
            
            //Calculate penalty fee and store
            if ( $moreMonths > 1 ) {

                $penaltyDays = $penaltyDays + ($moreMonths - 1) * 30;
        
                if ( $moreYears > 0 ) {
        
                    $penaltyDays = $penaltyDays + (360 * $moreYears);
        
                }
        
            }
        
            if ($moreMonths == 1) {
        
                if ( $moreYears > 0 ) {
        
                    $penaltyDays = $penaltyDays + (360 * $moreYears);
        
                }
                
            }
        
            if ($moreMonths == 0 ) {
                if ($moreYears >= 1) {
        
                    $penaltyDays = $penaltyDays + ((360 * $moreYears)-30);
        
                }
                
            }
        
            $generatedPenaltyFee = (round((($loanData->loanAmount) * ($loanData->penaltyRate) / 100) / 30 * $penaltyDays ,0));
            

        

            //if paid amount less than allInterest
            if ($data->transPaidAmount > $calAllInterest) {

                $newData->transPaidInterest = $data->transPaidAmount - ($data->transPaidAmount - $calAllInterest);
                //store penalty fee

                if (($data->transPaidAmount - $calAllInterest) >= $generatedPenaltyFee) {
                    
                    $newData->transPaidPenaltyFee = $generatedPenaltyFee;

                    //handle Extra money
                    if ($data->extraMoney == 'keep') {

                        //store extra money
                        $newData->transExtraMoney = ($data->transPaidAmount - $calAllInterest)-$generatedPenaltyFee;
                    
                    }elseif($data->extraMoney == 'reduce'){
                        
                        $newData->transReducedAmount = ($data->transPaidAmount - $calAllInterest)-$generatedPenaltyFee;

                        //reduce money from the loan
                        Loan::where('loanID', $data->transLoanID)
                        ->update([
                            'loanAmount' => ($loanData->loanAmount) - (($data->transPaidAmount - $calAllInterest)-$generatedPenaltyFee)
                            ]);

                    }
                    

                }else {

                        //handle Extra money
                    if ($data->extraMoney == 'keep') {

                        //store extra money
                        $newData->transExtraMoney = $data->transPaidAmount - $calAllInterest;
                    
                    }elseif($data->extraMoney == 'reduce'){

                        $newData->transReducedAmount = $data->transPaidAmount - $calAllInterest;

                        //reduce money from the loan
                        Loan::where('loanID', $data->transLoanID)
                        ->update([
                            'loanAmount' => ($loanData->loanAmount) - ($data->transPaidAmount - $calAllInterest)
                            ]);

                    }

                    $newData->transPaidPenaltyFee = $generatedPenaltyFee - ($generatedPenaltyFee - ($data->transPaidAmount - $calAllInterest));

                    //reset penalty fee store
                    $newData->transRestPenaltyFee = $generatedPenaltyFee - ($data->transPaidAmount - $calAllInterest);

                }
                
            }else {

                $newData->transPaidInterest = $data->transPaidAmount;
                //store penalty fee
                $newData->transPaidPenaltyFee = 0.0;
                //reset penalty fee store
                $newData->transRestPenaltyFee = $generatedPenaltyFee;
                //reset Interest
                $newData->transRestInterest = ($data->transPaidAmount - $calAllInterest) * (-1);

            }
             
            
            $newData->save();
            
            return redirect()->back()->with('message','successful');




        }else{

           
    
            $loanGotDateCal = $loanData->loanDate;

            $loanGotDate1 = new DateTime($loanGotDateCal);
            $currentDate1 = new DateTime($newDate);
            $loanDayInterval = $loanGotDate1->diff($currentDate1);
           
            $loanDayMoreDays = $loanDayInterval->d;

            /////////////////////////////////////////

            $lastPaidDateCal = $getTransactionData->paidDate;

            $lastPaidDate = new DateTime($lastPaidDateCal);
            $currentDate = new DateTime($newDate);
            $interval = $lastPaidDate->diff($currentDate);
            
            
            $moreDays = $interval->d;
            $moreMonths = $interval->m;
            $moreYears = $interval->y;



            if ($moreMonths > 0 && $moreDays > 0 && $moreYears > 0) {
                $calAllInterest = ($getTransactionData->transRestInterest - $getTransactionData->transExtraMoney) + (($loanData->loanAmount * ($loanData->loanRate/100)) * (($moreMonths+1) + ($moreYears * 12)));
            }

            if ($moreMonths == 0 && $moreDays > 0 && $moreYears > 0) {
                $calAllInterest = ($getTransactionData->transRestInterest - $getTransactionData->transExtraMoney) + ($loanData->loanAmount * ($loanData->loanRate/100)) *  ($moreYears * 12);
            }

            if ($moreMonths > 0 && $moreDays == 0 && $moreYears > 0) {
                $calAllInterest = ($getTransactionData->transRestInterest - $getTransactionData->transExtraMoney) + ($loanData->loanAmount * ($loanData->loanRate/100)) * ($moreMonths + ($moreYears * 12));
            }

            if ($moreMonths == 0 && $moreDays == 0 && $moreYears > 0) {
                $calAllInterest = ($getTransactionData->transRestInterest - $getTransactionData->transExtraMoney) + (($loanData->loanAmount * ($loanData->loanRate/100)) * ($moreYears * 12));
            }

            if ($moreMonths == 0 && $moreDays == 0 && $moreYears == 0) {

                if ($loanDayMoreDays > 0) {

                    $calAllInterest = ($getTransactionData->transRestInterest - $getTransactionData->transExtraMoney)+ (($loanData->loanAmount * ($loanData->loanRate/100)) * 1);

                }else{

                    $calAllInterest = ($getTransactionData->transRestInterest - $getTransactionData->transExtraMoney);

                }
                
            }

            if ($moreMonths > 0 && $moreDays > 0 && $moreYears == 0) {
                $calAllInterest = ($getTransactionData->transRestInterest - $getTransactionData->transExtraMoney) + (($loanData->loanAmount * ($loanData->loanRate/100)) * ($moreMonths + 1));
            }

            if ($moreMonths == 0 && $moreDays > 0 && $moreYears == 0) {
                $calAllInterest = ($getTransactionData->transRestInterest - $getTransactionData->transExtraMoney) + (($loanData->loanAmount * ($loanData->loanRate/100)) * 1);
            }

            if ($moreMonths > 0 && $moreDays == 0 && $moreYears == 0) {
                $calAllInterest = ($getTransactionData->transRestInterest - $getTransactionData->transExtraMoney) + (($loanData->loanAmount * ($loanData->loanRate/100)) * $moreMonths );
            }


            //get Due date from loan table
            $date = Carbon::createFromFormat('Y-m-d', $loanData->loanDate);
            $dueDay = $date->format('j');

            $date = Carbon::createFromFormat('Y-m-d', $newDate);
            //month
            $dueMonth = $date->format('n');
            $dueYear = $date->format('Y');

            $createdDate = Carbon::createFromDate($dueYear, $dueMonth, $dueDay)->toDateString();
            

            $CurrentMonthDueDate = Carbon::createFromFormat('Y-m-d', $createdDate);

            $today = Carbon::createFromFormat('Y-m-d', $newDate);

            if ($CurrentMonthDueDate > $today) {

                $calAllInterest = ($getTransactionData->transRestInterest - $getTransactionData->transExtraMoney);
                
            }
            
            ////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////

            $generatedPenaltyFee = 0;
            $penaltyDays = 0;
            $getDate = new DateTime();
            $newDate = $getDate->format('Y-m-d');
            
            $loanGotDateCal = $loanData->loanDate;
            $loanLastPaidDateCal = $getTransactionData->paidDate;

            ////////////////////////////////////////////////////////////////////////
            //get Due date from loan table
            $date = Carbon::createFromFormat('Y-m-d', $loanData->loanDate);
            $dueDay = $date->format('j');

            //get Current month and year
            $date = Carbon::createFromFormat('Y-m-d', $loanLastPaidDateCal);
            //month
            $dueMonth = $date->format('n');
            //year
            $dueYear = $date->format('Y');

            //create date according to current month year and Due date 
            $createdDate = Carbon::createFromDate($dueYear, $dueMonth, $dueDay)->toDateString();

            $CurrentMonthDueDate = Carbon::createFromFormat('Y-m-d', $createdDate);
            $newDate2 = Carbon::createFromFormat('Y-m-d', $newDate);

            //get different amount of Months
                
            $diff_in_days = $CurrentMonthDueDate->diffInDays($loanLastPaidDateCal) + 1;
            $diff_in_Months = $newDate2->diffInMonths($loanLastPaidDateCal);
            
            
            $diff_in_months2 = $newDate2->diffInMonths($loanGotDateCal);
            
        

            ////////////////////////////////////////////////////////////////////

            $loanGotDate = new DateTime($loanLastPaidDateCal);
            $currentDate = new DateTime($newDate);
            $interval = $loanGotDate->diff($currentDate);
            
            
            $moreDays = $interval->d;
            $moreMonths = $interval->m;
            $moreYears = $interval->y;

            ///////////////////////////////////////////////////////////////////
            
                        
            //current Loan Paying month and year
            $paidDateToGetTheMonth = Carbon::createFromFormat('Y-m-d', $newDate);
            $monthName = $paidDateToGetTheMonth->format('m');
            $year = $paidDateToGetTheMonth->format('Y');
            
            //get Due date from loan table
            $date = Carbon::createFromFormat('Y-m-d', $loanData->loanDate);
            $dueDate = $date->format('d');

            //dd($createdDate <= $loanLastPaidDateCal);
                ///////////////////////////////////////////////////////////////
            //dd($diff_in_days,$moreDays,$moreMonths,$moreYears);

            if (($createdDate <= $loanLastPaidDateCal) && $diff_in_months2 > 0 && ($diff_in_Months > 0 || $getTransactionData->transRestPenaltyFee > 0))
            {

                if ($monthName == 1 || $monthName == 3 || $monthName == 5 || $monthName == 7 || $monthName == 8 || $monthName == 10 || $monthName == 12) {
                    
                    $extraDays = 31 - $dueDate;

                    if ($moreDays >= $extraDays ) {
                    
                        $penaltyDays = $moreDays-1;
                        
                    }else{
                        $penaltyDays = $moreDays;
                    }

                }elseif ($monthName == 2) {
                    if ($year / 4 ==0) {
                        $extraDays = 29 - $dueDate;

                        if ($moreDays >= $extraDays ) {
                            
                            $penaltyDays = $moreDays+1;
                            
                        }else{
                            $penaltyDays = $moreDays;
                        }
                    }else{
                        $extraDays = 28 - $dueDate;

                        if ($moreDays >= $extraDays ) {

                            $penaltyDays = $moreDays+2;
                            
                        }else{
                            $penaltyDays = $moreDays;
                        }
                    }
                }else {

                    
                    $penaltyDays = $moreDays;

                }
            }

            
            if ( $moreMonths > 1 ) {

                $penaltyDays = $penaltyDays + ($moreMonths -1) * 30;

                if ( $moreYears > 0 ) {

                    $penaltyDays = $penaltyDays + (360 * $moreYears);

                }

            }

            if ($moreMonths == 1) {

                if ( $moreYears > 0 ) {

                    $penaltyDays = $penaltyDays + (360 * $moreYears);

                }

                //If there are rest penalty fee more than 1 month
                if ($moreDays > 0 && ($getTransactionData->transRestPenaltyFee > 0)) {

                    $penaltyDays = $penaltyDays + 30;

                }

                    
            }

            if ($moreMonths == 0 ) {
                if ($moreYears >= 1) {

                    $penaltyDays = $penaltyDays + ((360 * $moreYears)-30);

                }
                
            }

            $generatedPenaltyFee = (round((($loanData->loanAmount) * ($loanData->penaltyRate) / 100) / 30 * $penaltyDays ,0));

            //dd($generatedPenaltyFee);

            $allPenaltyFee = ($generatedPenaltyFee + $getTransactionData->transRestPenaltyFee);
            if ($calAllInterest < 0) {

                $allPenaltyFee = $allPenaltyFee + $calAllInterest;

            }

            //dd($allPenaltyFee,$calAllInterest);
            // Filter Minues values penalty fee
            // if ($allPenaltyFee <= 0) {

            //     $allPenaltyFee = 0;

            // }
            
            // Filter Minues values Interest
            // if ($calAllInterest < 0) {

            //     $calAllInterest = 0;

            // }

            
            
            //Store Data for Transaction
            $newData = new Transaction();
            $newData->paidDate = $data->paidDate;
            $newData->transPaidAmount = $data->transPaidAmount;
            $newData->transLoanID = $data->transLoanID;
            $newData->transDetails = $data->transDetails;
            $newData->transAllPaid = $data->transPaidAmount;


            $varPaidInterest = 0;
            $varPaidPenaltyFee = 0;
            $varRestInterest = 0;
            $varRestPenaltyFee = 0;
            $varExtraMoney = 0;

            $varRestPaidAmount = 0;
            

            if ($calAllInterest <= $data->transPaidAmount ) {
                
                $varPaidInterest = $calAllInterest;

                $varRestPaidAmount = $data->transPaidAmount - $calAllInterest;
            }else {

                $varPaidInterest = $data->transPaidAmount;

                $varRestInterest = $calAllInterest - $varPaidInterest;
            }

            if ($allPenaltyFee > 0) {
                
                if($varRestPaidAmount >= $allPenaltyFee){

                    $varPaidPenaltyFee = $allPenaltyFee;

                    $varExtraMoney = $varRestPaidAmount - $allPenaltyFee;

                }else{

                    $varPaidPenaltyFee = $varRestPaidAmount;

                    $varRestPenaltyFee = $calAllInterest - $varPaidPenaltyFee;

                }

            }else {
                if ($varRestPaidAmount > 0) {

                    $varExtraMoney = $varRestPaidAmount;

                }
            }
            

            $newData->transPaidInterest = $varPaidInterest;
            $newData->transPaidPenaltyFee = $varPaidPenaltyFee;
            $newData->transRestInterest = $varRestInterest;
            $newData->transRestPenaltyFee = $varRestPenaltyFee;
            $newData->transExtraMoney = $varExtraMoney;

            $newData->save();
            
            return redirect()->back()->with('message','successful');
        }        
    }
}
