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
        return redirect()->route('admin.allTransaction');
    }

    public function addingTransaction(Request $data)
    {
        $data->validate([

            'paidDate' => ['required','date'],
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
            //dd($days);
            //dd($interval->m,$interval->d);
            
            $moreDays = $interval->d;
            $moreMonths = $interval->m;
            $moreYears = $interval->y;
            
            //dd($moreDays,$moreMonths,$moreYears);

            if ($moreMonths > 0 && $moreDays > 0 && $moreYears > 0) {
                $calAllInterest = (($loanData->loanAmount * ($loanData->loanRate/100)) * ($moreMonths + 1 + ($moreYears * 12)));
                
            }

            if ($moreMonths == 0 && $moreDays > 0 && $moreYears > 0) {
                $calAllInterest = ($loanData->loanAmount * ($loanData->loanRate/100)) *  (1 + ($moreYears * 12));
            }

            if ($moreMonths > 0 && $moreDays == 0 && $moreYears > 0) {
                $calAllInterest = ($loanData->loanAmount * ($loanData->loanRate/100)) * ($moreMonths + 1 + ($moreYears * 12));
            }

            if ($moreMonths == 0 && $moreDays == 0 && $moreYears > 0) {
                $calAllInterest = (($loanData->loanAmount * ($loanData->loanRate/100)) * ($moreYears * 12 + 1));
            }

            if ($moreMonths == 0 && $moreDays == 0 && $moreYears == 0) {
                $calAllInterest = (($loanData->loanAmount * ($loanData->loanRate/100)) * 1);
            }

            if ($moreMonths > 0 && $moreDays > 0 && $moreYears == 0) {
                $calAllInterest = (($loanData->loanAmount * ($loanData->loanRate/100)) * ($moreMonths + 1 ));
            }

            if ($moreMonths == 0 && $moreDays > 0 && $moreYears == 0) {
                $calAllInterest = (($loanData->loanAmount * ($loanData->loanRate/100)) * 1);
                
            }

            if ($moreMonths > 0 && $moreDays == 0 && $moreYears == 0) {
                $calAllInterest = (($loanData->loanAmount * ($loanData->loanRate/100)) * ($moreMonths + 1));
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

            $newData = new Transaction();
            $newData->paidDate = $data->paidDate;
            $newData->transPaidAmount = $data->transPaidAmount;
            $newData->transLoanID = $data->transLoanID;
            $newData->transDetails = $data->transDetails;
            $newData->transAllPaid = $data->transPaidAmount + $getTransactionData->transAllPaid;

            $generatedPenaltyFee = 0;
            $allPenaltyFee = $getTransactionData->transRestPenaltyFee;

            $moreDays = 0;
            $moreMonths = 0;
            $moreYears = 0;
            $PenMoreMonths = 0;
            $PenMoreYears = 0;

            $loanGotDateCal = $loanData->loanDate;
            $loanLastPaidDateCal = $getTransactionData->paidDate;

            $allPaidAmount = $data->transPaidAmount + $getTransactionData->transExtraMoney;

            ////////////////////CREATING LAST PAID MONTH DUE DATE
            //get Due date from loan table
            $date = Carbon::createFromFormat('Y-m-d', $loanGotDateCal);
            $dueDay = $date->format('j');

            //get Last Paid month and year
            $date = Carbon::createFromFormat('Y-m-d', $loanLastPaidDateCal);
            //month
            $dueMonth = $date->format('n');
            //year
            $dueYear = $date->format('Y');

            //create date according to Last Paid month year and Due date 
            $createdDate = Carbon::createFromDate($dueYear, $dueMonth, $dueDay)->toDateString();
            $FormattedLastPaidDueDate = Carbon::createFromFormat('Y-m-d', $createdDate);

            //Created Last Paid Month Due Date
            $LastPaidDueDate = Carbon::createFromFormat('Y-m-d', $createdDate)->toDateString();



            if($createdDate <= $loanLastPaidDateCal){
                //Add One Month to Created Last Paid Month Due Date
                $AddOneMonLastPaidDueDate = $FormattedLastPaidDueDate->addMonth(1)->toDateString();

                //This part join with cal penalty fee
                if ($createdDate == $loanLastPaidDateCal) {
                    
                    $AddOneMonDateCal = new DateTime($createdDate);
                    $currentDate = new DateTime($data->paidDate);
                    $interval = $AddOneMonDateCal->diff($currentDate);
                    

                    $moreDays = $interval->d;
                    $moreMonths = $interval->m;
                    $moreYears = $interval->y;

                    //dd($moreDays,$moreMonths,$moreYears);
                }
                if ($allPenaltyFee > 0) {
                    
                    $AddOneMonDateCal = new DateTime($AddOneMonLastPaidDueDate);
                    $currentDate = new DateTime($data->paidDate);
                    $interval = $AddOneMonDateCal->diff($currentDate);
                    

                    $moreDays = $interval->d;
                    $moreMonths = $interval->m;
                    $moreYears = $interval->y;
                }
                
                //Cal Interest
                //Compare with new paying date
                if ($AddOneMonLastPaidDueDate <= $data->paidDate) {
                    
                    $AddOneMonDateCal = new DateTime($AddOneMonLastPaidDueDate);
                    $currentDate = new DateTime($data->paidDate);
                    $interval = $AddOneMonDateCal->diff($currentDate);
                    

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

                        $calAllInterest = ($getTransactionData->transRestInterest - $getTransactionData->transExtraMoney)+ (($loanData->loanAmount * ($loanData->loanRate/100)) * 1);
                
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

                }else {

                    $calAllInterest = $getTransactionData->transRestInterest;
                    //dd($calAllInterest);
                }

                //dd($moreDays,$moreMonths,$moreYears);
                //Calculate Penalty Fee
                if ( $moreDays > 0 || $moreMonths > 1 || $moreYears >= 1  ) {
                    
                    ////////////////////////////////////////////////////////////////////
                    if ($getTransactionData->transRestPenaltyFee != 0) {
                        
                        $getLastLoanPaidDate = new DateTime($loanLastPaidDateCal);
                        $getNewPaidDate = new DateTime($data->paidDate);
                        $interval = $getLastLoanPaidDate->diff($getNewPaidDate);

                    }elseif (!($allPaidAmount > $calAllInterest)) {

                        $getLastLoanPaidDate = new DateTime($createdDate);
                        $getNewPaidDate = new DateTime($data->paidDate);
                        $interval = $getLastLoanPaidDate->diff($getNewPaidDate);

                    }
                    
                    
                    $moreDays = $interval->d;
                    $PenMoreMonths = $interval->m;
                    $PenMoreYears = $interval->y;

                    ///////////////////////////////////////////////////////////////////
                    
                                
                    //current Loan Paying month and year
                    $paidDateToGetTheMonth = Carbon::createFromFormat('Y-m-d', $loanLastPaidDateCal);
                    $monthName = $paidDateToGetTheMonth->format('m');
                    $year = $paidDateToGetTheMonth->format('Y');
                    
                    //get Due date from loan table
                    $date = Carbon::createFromFormat('Y-m-d', $loanData->loanDate);
                    $dueDate = $date->format('d');

                    //dd($createdDate <= $loanLastPaidDateCal);
                        ///////////////////////////////////////////////////////////////
                    

                    $penaltyDays = 0;

                    if ($moreDays != 0) {
                        
                        if ($monthName == 1 || $monthName == 3 || $monthName == 5 || $monthName == 7 || $monthName == 8 || $monthName == 10 || $monthName == 12) {
                            
                            $extraDays = 31 - $dueDate;

                            if ($moreDays >= $extraDays ) {
                            
                                $penaltyDays = $moreDays-1;
                                
                            }else{
                                $penaltyDays = $moreDays;
                            }

                        }elseif ($monthName == 2) {
                            if ($year / 4 == 0) {
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

                    
                    if ( $PenMoreMonths > 0 ) {

                        //Reduce one month if more than one month has NOT paid
                        if ($moreMonths > 1) {
                            $penaltyDays = $penaltyDays + (($PenMoreMonths - 1) * 30);
                        }else {
                            $penaltyDays = $penaltyDays + ($PenMoreMonths * 30);
                        }
                        
                    }

                    

                    if ($PenMoreYears > 0) {

                        //Reduce one month if more than one month has NOT paid
                        if ($moreYears >= 1) {
                            $penaltyDays = $penaltyDays + (330 * $PenMoreYears);
                        }else {
                            $penaltyDays = $penaltyDays + (360 * $PenMoreYears);
                        }
                        
                    }
                        
                   // dd($penaltyDays);

                    
                   $generatedPenaltyFee = (round((($loanData->loanAmount) * ($loanData->penaltyRate) / 100) / 30 * $penaltyDays ,0));
                   //$generatedPenaltyFee = ((($loanData->loanAmount) * ($loanData->penaltyRate) / 100) / 30 * $penaltyDays);

                   //dd($generatedPenaltyFee);

                   $allPenaltyFee = ($generatedPenaltyFee + $getTransactionData->transRestPenaltyFee);

                    
                }

                //dd($calAllInterest,$allPenaltyFee);
                
                //if paid amount less than allInterest
                if ($allPaidAmount > $calAllInterest) {
                    
                    $newData->transPaidInterest = $allPaidAmount - ($allPaidAmount - $calAllInterest);
                    //store penalty fee

                    if (($allPaidAmount - $calAllInterest) >= $allPenaltyFee) {
                        
                        $newData->transPaidPenaltyFee = $allPenaltyFee;

                        //handle Extra money
                        if ($data->extraMoney == 'keep') {

                            //store extra money
                            $newData->transExtraMoney = ($allPaidAmount - $calAllInterest)-$allPenaltyFee;
                        
                        }elseif($data->extraMoney == 'reduce'){
                            
                            $newData->transReducedAmount = ($allPaidAmount - $calAllInterest)-$allPenaltyFee;

                            //reduce money from the loan
                            Loan::where('loanID', $data->transLoanID)
                            ->update([
                                'loanAmount' => ($loanData->loanAmount) - (($allPaidAmount - $calAllInterest)-$allPenaltyFee)
                                ]);

                        }
                        

                    }else {

                            //handle Extra money
                        if ($data->extraMoney == 'keep' && $allPenaltyFee == 0) {

                            //store extra money
                            $newData->transExtraMoney = $allPaidAmount - $calAllInterest;
                        
                        }elseif($data->extraMoney == 'reduce'){

                            $newData->transReducedAmount = $allPaidAmount - $calAllInterest;

                            //reduce money from the loan
                            Loan::where('loanID', $data->transLoanID)
                            ->update([
                                'loanAmount' => ($loanData->loanAmount) - ($allPaidAmount - $calAllInterest)
                                ]);

                        }

                        $newData->transPaidPenaltyFee = $allPenaltyFee - ($allPenaltyFee - ($allPaidAmount - $calAllInterest));

                        //reset penalty fee store
                        $newData->transRestPenaltyFee = $allPenaltyFee - ($allPaidAmount - $calAllInterest);

                    }
                    
                }else {
                    
                    $newData->transPaidInterest = $allPaidAmount;
                    //store penalty fee
                    $newData->transPaidPenaltyFee = 0.0;
                    //reset penalty fee store
                    $newData->transRestPenaltyFee = $allPenaltyFee;
                    //reset Interest
                    $newData->transRestInterest = ($allPaidAmount - $calAllInterest) * (-1);

                }
                
                
                if($newData->save()){
                    return redirect()->back()->with('message','successful');
                }
                
                

                
            }else {
                //Compare with new paying date
                if ($createdDate <= $data->paidDate) {
                    
                    //Calculate Interest
                    $LatPayDueDateCal = new DateTime($createdDate);
                    $currentDate = new DateTime($data->paidDate);
                    $interval = $LatPayDueDateCal->diff($currentDate);
                    
                    
                    $moreDays = $interval->d;
                    $moreMonths = $interval->m;
                    $moreYears = $interval->y;

                    //dd($moreDays, $moreMonths,$moreYears);

                    if ($moreMonths > 0 && $moreDays > 0 && $moreYears > 0) {
                        $calAllInterest = ($getTransactionData->transRestInterest) + (($loanData->loanAmount * ($loanData->loanRate/100)) * (($moreMonths+1) + ($moreYears * 12)));
                    }

                    if ($moreMonths == 0 && $moreDays > 0 && $moreYears > 0) {
                        $calAllInterest = ($getTransactionData->transRestInterest) + ($loanData->loanAmount * ($loanData->loanRate/100)) *  ($moreYears * 12);
                    }

                    if ($moreMonths > 0 && $moreDays == 0 && $moreYears > 0) {
                        $calAllInterest = ($getTransactionData->transRestInterest) + ($loanData->loanAmount * ($loanData->loanRate/100)) * ($moreMonths + ($moreYears * 12));
                    }

                    if ($moreMonths == 0 && $moreDays == 0 && $moreYears > 0) {
                        $calAllInterest = ($getTransactionData->transRestInterest) + (($loanData->loanAmount * ($loanData->loanRate/100)) * ($moreYears * 12));
                    }

                    if ($moreMonths == 0 && $moreDays == 0 && $moreYears == 0) {

                        $calAllInterest = ($getTransactionData->transRestInterest)+ (($loanData->loanAmount * ($loanData->loanRate/100)) * 1);
                
                    }

                    if ($moreMonths > 0 && $moreDays > 0 && $moreYears == 0) {
                        $calAllInterest = ($getTransactionData->transRestInterest) + (($loanData->loanAmount * ($loanData->loanRate/100)) * ($moreMonths + 1));
                    }

                    if ($moreMonths == 0 && $moreDays > 0 && $moreYears == 0) {
                        $calAllInterest = ($getTransactionData->transRestInterest ) + (($loanData->loanAmount * ($loanData->loanRate/100)) * 1);
                    }

                    if ($moreMonths > 0 && $moreDays == 0 && $moreYears == 0) {
                        $calAllInterest = ($getTransactionData->transRestInterest) + (($loanData->loanAmount * ($loanData->loanRate/100)) * $moreMonths );
                    }
                    //dd($calAllInterest);

                }else {

                    $calAllInterest = $getTransactionData->transRestInterest;
                    //dd($calAllInterest,'today date small');
                }

                //Calculate Penalty Fee
                if ( $getTransactionData->transRestPenaltyFee != 0 ||($moreDays > 0 || $moreMonths > 1 || $moreYears >= 1)  ) {
                    
                    ////////////////////////////////////////////////////////////////////
                    if ($getTransactionData->transRestPenaltyFee != 0) {
                        
                        $getLastLoanPaidDate = new DateTime($loanLastPaidDateCal);
                        $getNewPaidDate = new DateTime($data->paidDate);
                        $interval = $getLastLoanPaidDate->diff($getNewPaidDate);

                    }elseif (!($allPaidAmount > $calAllInterest)) {

                        $getLastLoanPaidDate = new DateTime($createdDate);
                        $getNewPaidDate = new DateTime($data->paidDate);
                        $interval = $getLastLoanPaidDate->diff($getNewPaidDate);

                    }
                    
                    
                    
                    $moreDays = $interval->d + 1;
                    $PenMoreMonths = $interval->m;
                    $PenMoreYears = $interval->y;

                    ///////////////////////////////////////////////////////////////////
                    
                                
                    //current Loan Paying month and year
                    $paidDateToGetTheMonth = Carbon::createFromFormat('Y-m-d', $loanLastPaidDateCal);
                    $monthName = $paidDateToGetTheMonth->format('m');
                    $year = $paidDateToGetTheMonth->format('Y');
                    
                    //get Due date from loan table
                    $date = Carbon::createFromFormat('Y-m-d', $loanData->loanDate);
                    $dueDate = $date->format('d');

                    //dd($createdDate <= $loanLastPaidDateCal);
                        ///////////////////////////////////////////////////////////////
                    //dd($moreDays,$moreMonths,$moreYears);

                    $penaltyDays = 0;

                    if ($moreDays != 0) {
                        
                        if ($monthName == 1 || $monthName == 3 || $monthName == 5 || $monthName == 7 || $monthName == 8 || $monthName == 10 || $monthName == 12) {
                            
                            $extraDays = 31 - $dueDate;

                            if ($moreDays >= $extraDays ) {
                            
                                $penaltyDays = $moreDays-1;
                                
                            }else{
                                $penaltyDays = $moreDays;
                            }

                        }elseif ($monthName == 2) {
                            if ($year / 4 == 0) {
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

                    
                    if ( $PenMoreMonths > 0 ) {

                        //Reduce one month if more than one month has NOT paid
                        if ($moreMonths > 1) {
                            $penaltyDays = $penaltyDays + (($PenMoreMonths - 1) * 30);
                        }else {
                            $penaltyDays = $penaltyDays + ($PenMoreMonths * 30);
                        }
                        
                    }

                    

                    if ($PenMoreYears > 0) {

                        //Reduce one month if more than one month has NOT paid
                        if ($moreYears >= 1) {
                            $penaltyDays = $penaltyDays + (330 * $PenMoreYears);
                        }else {
                            $penaltyDays = $penaltyDays + (360 * $PenMoreYears);
                        }
                        
                    }
                        
                    //dd($penaltyDays);

                    $generatedPenaltyFee = (round((($loanData->loanAmount) * ($loanData->penaltyRate) / 100) / 30 * $penaltyDays ,0));

                    //$generatedPenaltyFee = ((($loanData->loanAmount) * ($loanData->penaltyRate) / 100) / 30 * $penaltyDays);

                    //dd($generatedPenaltyFee);

                    $allPenaltyFee = ($generatedPenaltyFee + $getTransactionData->transRestPenaltyFee);

                    
                }
                //dd($calAllInterest,$generatedPenaltyFee,$allPenaltyFee);


                //if paid amount less than allInterest
                if ($allPaidAmount > $calAllInterest) {
                    
                    $newData->transPaidInterest = $allPaidAmount - ($allPaidAmount - $calAllInterest);
                    //store penalty fee

                    if (($allPaidAmount - $calAllInterest) >= $generatedPenaltyFee) {
                        
                        $newData->transPaidPenaltyFee = $generatedPenaltyFee;

                        //handle Extra money
                        if ($data->extraMoney == 'keep') {

                            //store extra money
                            $newData->transExtraMoney = (($allPaidAmount - $calAllInterest)-$generatedPenaltyFee);
                        
                        }elseif($data->extraMoney == 'reduce'){
                            
                            $newData->transReducedAmount = (($allPaidAmount - $calAllInterest)-$generatedPenaltyFee);

                            //reduce money from the loan
                            Loan::where('loanID', $data->transLoanID)
                            ->update([
                                'loanAmount' => ($loanData->loanAmount) - (($allPaidAmount - $calAllInterest) - $generatedPenaltyFee)
                                ]);

                        }
                        

                    }else {

                            //handle Extra money
                        if ($data->extraMoney == 'keep' && $allPenaltyFee == 0) {

                            //store extra money
                            $newData->transExtraMoney = $allPaidAmount - $calAllInterest;
                        
                        }elseif($data->extraMoney == 'reduce'){

                            $newData->transReducedAmount = $allPaidAmount - $calAllInterest;

                            //reduce money from the loan
                            Loan::where('loanID', $data->transLoanID)
                            ->update([
                                'loanAmount' => ($loanData->loanAmount) - ($allPaidAmount - $calAllInterest)
                                ]);

                        }

                        $newData->transPaidPenaltyFee = $allPenaltyFee - ($allPenaltyFee - ($allPaidAmount - $calAllInterest));

                        //reset penalty fee store
                        $newData->transRestPenaltyFee = $allPenaltyFee - ($allPaidAmount - $calAllInterest);

                    }
                    
                }else {
                    
                    $newData->transPaidInterest = $allPaidAmount;
                    //store penalty fee
                    $newData->transPaidPenaltyFee = 0.0;
                    //reset penalty fee store
                    $newData->transRestPenaltyFee = $allPenaltyFee;
                    //reset Interest
                    $newData->transRestInterest = ($allPaidAmount - $calAllInterest) * (-1);

                }
                
                
                if($newData->save()){
                    return redirect()->back()->with('message','successful');
                }
            }
                    
       

            
        }        
    }
}
