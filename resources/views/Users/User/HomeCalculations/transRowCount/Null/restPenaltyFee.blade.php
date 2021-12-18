<?php

    $generatedPenaltyFee = 0;
    $penaltyDays = 0;
    $getDate = new DateTime();
    $newDate = $getDate->format('Y-m-d');
    
    $loanGotDateCal = $item->loanDate;

    ////////////////////////////////////////////////////////////////////////
    //get Due date from loan table
    $date = Carbon\Carbon::createFromFormat('Y-m-d', $item->loanDate);
    $dueDay = $date->format('j');

    //get Current month and year
    $date = Carbon\Carbon::createFromFormat('Y-m-d', $newDate);
    //month
    $dueMonth = $date->format('n');
    //year
    $dueYear = $date->format('Y');

    //create date according to current month year and Due date 
    $createdDate = Carbon\Carbon::createFromDate($dueYear, $dueMonth, $dueDay)->toDateString();

    $CurrentMonthDueDate = Carbon\Carbon::createFromFormat('Y-m-d', $createdDate);
    $newDate2 = Carbon\Carbon::createFromFormat('Y-m-d', $newDate);

    //get different amount of Months
    $diff_in_months = $CurrentMonthDueDate->diffInMonths($loanGotDateCal);
    
    $diff_in_months2 = $newDate2->diffInMonths($loanGotDateCal);

    ////////////////////////////////////////////////////////////////////

    $loanGotDate = new DateTime($loanGotDateCal);
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
    $date = Carbon\Carbon::createFromFormat('Y-m-d', $item->loanDate);
    $dueDate = $date->format('d');

    ///////////////////////////////////////////////////////////////

    if ($diff_in_months >=1 && $diff_in_months2 > 0)
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

    $generatedPenaltyFee = (round((($item->loanAmount) * ($item->penaltyRate) / 100) / 30 * $penaltyDays ,0));
    

?>

{{$generatedPenaltyFee}}

{{-- {{$moreDays}}
{{$moreMonths}}
{{$moreYears}} --}}