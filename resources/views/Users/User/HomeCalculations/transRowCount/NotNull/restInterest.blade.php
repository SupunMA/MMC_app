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
      if ($calAllInterest < 0) {

        $calAllInterest = 0;

      }
?>


{{$calAllInterest}}
{{-- {{$moreDays}}
{{$moreMonths}}
{{$moreYears}}
{{$loanDayMoreDays}} --}}
