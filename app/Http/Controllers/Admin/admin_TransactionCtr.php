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
// ,'unique:loans,loanLandID'
        ]);

        //dd($data->extraMoney);

        $getTransactionData = Transaction::where('transLoanID',$data->transLoanID)
        ->orderBy('transID', 'desc')->first();

        
        

        if ($getTransactionData == Null){
            
            //$user = Transaction::create($data->all());
            $loanData = Loan::where('loanID', $data->transLoanID)
            ->get()->first();

            
            $gotLoanDate = new DateTime($loanData->loanDate);
            $gotPaidDate = new DateTime($data->paidDate);
            $interval = $gotLoanDate->diff($gotPaidDate);
            $days = $interval->format('%a');//now do whatever you like with $days
            
            //dd($interval->m,$interval->d);
            
            $moreDays = $interval->d;
            $moreMonths = $interval->m;
            
            if ($moreDays != 0) {
                
                //current Loan Paying month and year
                $paidDateToGetTheMonth = Carbon::createFromFormat('Y-m-d', $data->paidDate);
                $monthName = $paidDateToGetTheMonth->format('m');
                $year = $paidDateToGetTheMonth->format('Y');
                
                //get Due date from loan table
                $date = Carbon::createFromFormat('Y-m-d', $loanData->loanDate);
                $dueDate = $date->format('d');

                if ($monthName == 1 || $monthName == 3 || $monthName == 5 || $monthName == 7 || $monthName == 8 || $monthName == 10 || $monthName == 12) {
                    
                    $extraDays = 31 - $dueDate;

                    if ($moreDays >= $extraDays ) {
    
                        $penaltyDays = $moreDays-1;
                        
                    }else{
                        $penaltyDays = $moreDays;
                    }

                }
                if ($monthName == 2) {
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
                }
                
            }
            // dd($penaltyDays);
            
            $newData = new Transaction();
            $newData->paidDate = $data->paidDate;
            $newData->transPaidAmount = $data->transPaidAmount;
            $newData->transLoanID = $data->transLoanID;
            $newData->transDetails = $data->transDetails;
            $newData->transAllPaid = $data->transPaidAmount;

            // $newData->transReducedAmount = $getTransactionData->transReducedAmount;
            
         //Calculate penalty fee and store
            $generatedPenaltyFee = (round((($loanData->loanAmount) * ($loanData->penaltyRate) / 100) / 30 * $penaltyDays,0));
            

         //Calculate paid interest
            //cal interest for months
            $calAllInterest = (($loanData->loanAmount * ($loanData->loanRate/100)) * $moreMonths);

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

                        //reduce money from the loan
                        Loan::where('loanID', $data->transLoanID)
                        ->update([
                            'loanAmount' => ($loanData->loanAmount) - (($data->transPaidAmount - $calAllInterest)-$generatedPenaltyFee)
                            ]);

                    }
                    

                }else {
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
            

            // $newData->transRestInterest = $getTransactionData->transRestInterest;
            // $newData->transRestPenaltyFee = $getTransactionData->transRestPenaltyFee;
            
            $newData->save();
            
            return redirect()->back()->with('message','successful');
            
            
            


            
            //->route('your_url_where_you_want_to_redirect');

        }else{

            $loanDate = Loan::where('loanID', $data->transLoanID)
            ->get()->first();

            
            // $newData = new Transaction();
            // $newData->paidDate = $data->get('paidDate');
            // $newData->transPaidAmount = $data->get('transPaidAmount');
            // $newData->transLoanID = $data->get('transLoanID');
            // $newData->transDetails = $data->get('transDetails');

            // $newData->transAllPaid = ($getTransactionData->transAllPaid) + ($data->transPaidAmount);
            // $newData->transReducedAmount = $getTransactionData->transReducedAmount;
            // $newData->transPaidInterest = $getTransactionData->transPaidInterest;
            // $newData->transPaidPenaltyFee = $getTransactionData->transPaidPenaltyFee;
            // $newData->transRestInterest = $getTransactionData->transRestInterest;
            // $newData->transRestPenaltyFee = $getTransactionData->transRestPenaltyFee;
            
            // $newData->save();
            
            // return redirect()->back()->with('message','successful');

            
           

            
          

           
            

           // return $monthName;
            
    
            

        }
        
    }
}
