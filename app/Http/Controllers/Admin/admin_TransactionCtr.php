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
        return view('Users.Admin.Transactions.allTransaction');
    }

    public function addingTransaction(Request $data)
    {
        $data->validate([

            'paidDate' => ['required'],
            'transPaidAmount' => ['required','max:99999999','numeric'],
            'transLoanID' => ['required']
// 
        ]);

        //dd($data->extraMoney);

        $getTransactionData = Transaction::where('transLoanID',$data->transLoanID)
        ->orderBy('transID', 'desc')->first();

        $getDate = new DateTime();
        $newDate = $getDate->format('Y-m-d');

        if ($getTransactionData == Null){
            
            //$user = Transaction::create($data->all());
            $loanData = Loan::where('loanID', $data->transLoanID)
            ->get()->first();


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

            $loanDate = Loan::where('loanID', $data->transLoanID)
            ->get()->first();

            
            //return $getTransactionData;
            return 'There is data in Trans table';
            
    
            

        }
        
    }
}
