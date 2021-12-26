
{{-- rest Interest Calculation and substract extra money from reset penalty fee--}}
<?php

$getDate = new DateTime();
    $newDate = $getDate->format('Y-m-d');
    
        $loanGotDateCal = $transactionData->loanDate;

            $loanGotDate1 = new DateTime($loanGotDateCal);
            $currentDate1 = new DateTime($newDate);
            $loanDayInterval = $loanGotDate1->diff($currentDate1);
           
            $loanDayMoreDays = $loanDayInterval->d;

            /////////////////////////////////////////

        $lastPaidDateCal = $transactionData->paidDate;

            $lastPaidDate = new DateTime($lastPaidDateCal);
            $currentDate = new DateTime($newDate);
            $interval = $lastPaidDate->diff($currentDate);
            
            
            $moreDays = $interval->d;
            $moreMonths = $interval->m;
            $moreYears = $interval->y;



            if ($moreMonths > 0 && $moreDays > 0 && $moreYears > 0) {
                $calAllInterest = ($transactionData->transRestInterest - $transactionData->transExtraMoney) + (($transactionData->loanAmount * ($transactionData->loanRate/100)) * (($moreMonths+1) + ($moreYears * 12)));
            }

            if ($moreMonths == 0 && $moreDays > 0 && $moreYears > 0) {
                $calAllInterest = ($transactionData->transRestInterest - $transactionData->transExtraMoney) + ($transactionData->loanAmount * ($transactionData->loanRate/100)) *  ($moreYears * 12);
            }

            if ($moreMonths > 0 && $moreDays == 0 && $moreYears > 0) {
                $calAllInterest = ($transactionData->transRestInterest - $transactionData->transExtraMoney) + ($transactionData->loanAmount * ($transactionData->loanRate/100)) * ($moreMonths + ($moreYears * 12));
            }

            if ($moreMonths == 0 && $moreDays == 0 && $moreYears > 0) {
                $calAllInterest = ($transactionData->transRestInterest - $transactionData->transExtraMoney) + (($transactionData->loanAmount * ($transactionData->loanRate/100)) * ($moreYears * 12));
            }

            if ($moreMonths == 0 && $moreDays == 0 && $moreYears == 0) {

                if ($loanDayMoreDays > 0) {

                    $calAllInterest = ($transactionData->transRestInterest - $transactionData->transExtraMoney)+ (($transactionData->loanAmount * ($transactionData->loanRate/100)) * 1);

                }else{

                    $calAllInterest = ($transactionData->transRestInterest - $transactionData->transExtraMoney);

                }
                
            }

            if ($moreMonths > 0 && $moreDays > 0 && $moreYears == 0) {
                $calAllInterest = ($transactionData->transRestInterest - $transactionData->transExtraMoney) + (($transactionData->loanAmount * ($transactionData->loanRate/100)) * ($moreMonths + 1));
            }

            if ($moreMonths == 0 && $moreDays > 0 && $moreYears == 0) {
                $calAllInterest = ($transactionData->transRestInterest - $transactionData->transExtraMoney) + (($transactionData->loanAmount * ($transactionData->loanRate/100)) * 1);
            }

            if ($moreMonths > 0 && $moreDays == 0 && $moreYears == 0) {
                $calAllInterest = ($transactionData->transRestInterest - $transactionData->transExtraMoney) + (($transactionData->loanAmount * ($transactionData->loanRate/100)) * $moreMonths );
            }


            //get Due date from loan table
            $date = Carbon\Carbon::createFromFormat('Y-m-d', $transactionData->loanDate);
            $dueDay = $date->format('j');

            $date = Carbon\Carbon::createFromFormat('Y-m-d', $newDate);
            //month
            $dueMonth = $date->format('n');
            $dueYear = $date->format('Y');

            $createdDate = Carbon\Carbon::createFromDate($dueYear, $dueMonth, $dueDay)->toDateString();
            

            $CurrentMonthDueDate = Carbon\Carbon::createFromFormat('Y-m-d', $createdDate);

            $today = Carbon\Carbon::createFromFormat('Y-m-d', $newDate);

            if ($CurrentMonthDueDate > $today) {

                $calAllInterest = ($transactionData->transRestInterest - $transactionData->transExtraMoney);
                
            }

           // $check = Carbon::now()->between($startDate, $endDate);
/// Filter Minues values 
      
?>








<?php

    $generatedPenaltyFee = 0;
    $penaltyDays = 0;
    $getDate = new DateTime();
    $newDate = $getDate->format('Y-m-d');
    
    $loanGotDateCal = $transactionData->loanDate;
    $loanLastPaidDateCal = $transactionData->paidDate;

    ////////////////////////////////////////////////////////////////////////
    //get Due date from loan table
    $date = Carbon\Carbon::createFromFormat('Y-m-d', $transactionData->loanDate);
    $dueDay = $date->format('j');

    //get Current month and year
    $date = Carbon\Carbon::createFromFormat('Y-m-d', $loanLastPaidDateCal);
    //month
    $dueMonth = $date->format('n');
    //year
    $dueYear = $date->format('Y');

    //create date according to current month year and Due date 
    $createdDate = Carbon\Carbon::createFromDate($dueYear, $dueMonth, $dueDay)->toDateString();

    $CurrentMonthDueDate = Carbon\Carbon::createFromFormat('Y-m-d', $createdDate);
    $newDate2 = Carbon\Carbon::createFromFormat('Y-m-d', $newDate);

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
    $paidDateToGetTheMonth = Carbon\Carbon::createFromFormat('Y-m-d', $newDate);
    $monthName = $paidDateToGetTheMonth->format('m');
    $year = $paidDateToGetTheMonth->format('Y');
    
    //get Due date from loan table
    $date = Carbon\Carbon::createFromFormat('Y-m-d', $transactionData->loanDate);
    $dueDate = $date->format('d');

    //dd($createdDate <= $loanLastPaidDateCal);
        ///////////////////////////////////////////////////////////////
    //dd($diff_in_days,$moreDays,$moreMonths,$moreYears);

    if (($createdDate <= $loanLastPaidDateCal) && $diff_in_months2 > 0 && ($diff_in_Months > 0 || $transactionData->transRestPenaltyFee > 0))
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
        if ($moreDays > 0 && ($transactionData->transRestPenaltyFee > 0)) {

            $penaltyDays = $penaltyDays + 30;

        }

            
    }

    if ($moreMonths == 0 ) {
        if ($moreYears >= 1) {

            $penaltyDays = $penaltyDays + ((360 * $moreYears)-30);

        }
        
    }

    $generatedPenaltyFee = (round((($transactionData->loanAmount) * ($transactionData->penaltyRate) / 100) / 30 * $penaltyDays ,0));

    //dd($generatedPenaltyFee);

    $allPenaltyFee = ($generatedPenaltyFee + $transactionData->transRestPenaltyFee);
    if ($calAllInterest < 0) {

        $allPenaltyFee = $allPenaltyFee + $calAllInterest;

}

    // Filter Minues values 
    if ($allPenaltyFee <= 0) {

        $allPenaltyFee = 0;

    }
    

?>


{{$allPenaltyFee}}

