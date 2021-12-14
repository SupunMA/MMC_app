<?php

    $getDate = new DateTime();
    $newDate = $getDate->format('Y-m-d');
    
    $lastPaidDateCal = $item->paidDate;

            $lastPaidDate = new DateTime($lastPaidDateCal);
            $currentDate = new DateTime($newDate);
            $interval = $lastPaidDate->diff($currentDate);
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
                $calAllInterest = ($item->transRestInterest - $item->transExtraMoney) + (($item->loanAmount * ($item->loanRate/100)) * ($moreMonths + 1) + ($moreYears * 12));
            }

            if ($moreMonths == 0 && $moreDays > 0 && $moreYears > 0) {
                $calAllInterest = ($item->transRestInterest - $item->transExtraMoney) + ($item->loanAmount * ($item->loanRate/100)) *  (1 + ($moreYears * 12));
            }

            if ($moreMonths > 0 && $moreDays == 0 && $moreYears > 0) {
                $calAllInterest = ($item->transRestInterest - $item->transExtraMoney) + ($item->loanAmount * ($item->loanRate/100)) * ($moreMonths + ($moreYears * 12));
            }

            if ($moreMonths == 0 && $moreDays == 0 && $moreYears > 0) {
                $calAllInterest = ($item->transRestInterest - $item->transExtraMoney) + (($item->loanAmount * ($item->loanRate/100)) * ($moreYears * 12));
            }

            if ($moreMonths == 0 && $moreDays == 0 && $moreYears == 0) {
                $calAllInterest = ($item->transRestInterest - $item->transExtraMoney) + (($item->loanAmount * ($item->loanRate/100)) * 1);
            }

            if ($moreMonths > 0 && $moreDays > 0 && $moreYears == 0) {
                $calAllInterest = ($item->transRestInterest - $item->transExtraMoney) + (($item->loanAmount * ($item->loanRate/100)) * ($moreMonths + 1));
            }

            if ($moreMonths == 0 && $moreDays > 0 && $moreYears == 0) {
                $calAllInterest = ($item->transRestInterest - $item->transExtraMoney) + (($item->loanAmount * ($item->loanRate/100)) * 1);
            }

            if ($moreMonths > 0 && $moreDays == 0 && $moreYears == 0) {
                $calAllInterest = ($item->transRestInterest - $item->transExtraMoney) + (($item->loanAmount * ($item->loanRate/100)) * $moreMonths );
            }


            //get Due date from loan table
            $date = Carbon\Carbon::createFromFormat('Y-m-d', $item->loanDate);
            $dueDay = $date->format('j');

            $date = Carbon\Carbon::createFromFormat('Y-m-d', $newDate);
            //month
            $dueMonth = $date->format('n');
            $dueYear = $date->format('Y');

            $createdDate = Carbon\Carbon::createFromDate($dueYear, $dueMonth, $dueDay)->toDateString();
            

            $CurrentMonthDueDate = Carbon\Carbon::createFromFormat('Y-m-d', $createdDate);

            $today = Carbon\Carbon::createFromFormat('Y-m-d', $newDate);

            if ($CurrentMonthDueDate > $today) {

                $calAllInterest = ($item->transRestInterest - $item->transExtraMoney);
                
            }

           // $check = Carbon::now()->between($startDate, $endDate);

      
?>


{{$calAllInterest}}
{{-- {{$moreDays}}
{{$moreMonths}}
{{$moreYears}} --}}