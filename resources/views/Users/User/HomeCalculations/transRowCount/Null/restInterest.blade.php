<?php

    $getDate = new DateTime();
    $newDate = $getDate->format('Y-m-d');
    
    $loanGotDateCal = $item->loanDate;

            $loanGotDate = new DateTime($loanGotDateCal);
            $currentDate = new DateTime($newDate);
            $interval = $loanGotDate->diff($currentDate);
           
            $moreDays = $interval->d;
            $moreMonths = $interval->m;
            $moreYears = $interval->y;

            if ($moreMonths > 0 && $moreDays > 0 && $moreYears > 0) {
                $calAllInterest = (($item->loanAmount * ($item->loanRate/100)) * ($moreMonths + 1 + ($moreYears * 12)));
                
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
{{$moreMonths}}
{{$moreYears}} --}}