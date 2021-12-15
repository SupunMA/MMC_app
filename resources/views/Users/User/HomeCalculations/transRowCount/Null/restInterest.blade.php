<?php

    $getDate = new DateTime();
    $newDate = $getDate->format('Y-m-d');
    
    $loanGotDateCal = $item->loanDate;

            $loanGotDate = new DateTime($loanGotDateCal);
            $currentDate = new DateTime($newDate);
            $interval = $loanGotDate->diff($currentDate);
            //$days = $interval->format('%a');
            
            //dd($interval->m,$interval->d);
            
            $moreDays = $interval->d;
            $moreMonths = $interval->m;
            $moreYears = $interval->y;

                //current Loan Paying month and year
            // $paidDateToGetTheMonth = Carbon\Carbon::createFromFormat('Y-m-d', $newDate);
            // $monthName = $paidDateToGetTheMonth->format('m');
            // $year = $paidDateToGetTheMonth->format('Y');

                //get Due date from loan table
            // $date = Carbon\Carbon::createFromFormat('Y-m-d', $item->loanDate);
            // $dueDate = $date->format('d');

                //get today date
            // $date = Carbon\Carbon::createFromFormat('Y-m-d', $newDate);
            // $newDateDay = $date->format('d');

            if ($moreMonths > 0 && $moreDays > 0 && $moreYears > 0) {
                $calAllInterest = (($item->loanAmount * ($item->loanRate/100)) * (($moreMonths + 1) + ($moreYears * 12)));
                //$calAllInterest =0;
            }

            if ($moreMonths == 0 && $moreDays > 0 && $moreYears > 0) {
                $calAllInterest = ($item->loanAmount * ($item->loanRate/100)) *  (1 + ($moreYears * 12));
            }

            if ($moreMonths > 0 && $moreDays == 0 && $moreYears > 0) {
                $calAllInterest = ($item->loanAmount * ($item->loanRate/100)) * ($moreMonths + ($moreYears * 12));
            }

            if ($moreMonths == 0 && $moreDays == 0 && $moreYears > 0) {
                $calAllInterest = (($item->loanAmount * ($item->loanRate/100)) * ($moreYears * 12));
            }

            if ($moreMonths == 0 && $moreDays == 0 && $moreYears == 0) {
                $calAllInterest = 0;
            }

            if ($moreMonths > 0 && $moreDays > 0 && $moreYears == 0) {
                $calAllInterest = (($item->loanAmount * ($item->loanRate/100)) * ($moreMonths + 1));
            }

            if ($moreMonths == 0 && $moreDays > 0 && $moreYears == 0) {
                $calAllInterest = (($item->loanAmount * ($item->loanRate/100)) * 1);
                
            }

            if ($moreMonths > 0 && $moreDays == 0 && $moreYears == 0) {
                $calAllInterest = (($item->loanAmount * ($item->loanRate/100)) * $moreMonths );
            }

?>


{{$calAllInterest}}

{{-- {{$moreDays}}
<br>
{{$moreMonths}}
<br>
{{$moreYears}} --}}

{{-- {{$diff_in_months}} --}}