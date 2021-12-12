<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transID';
    protected $table = 'transactions';

    protected $fillable = [
            'paidDate',
            'transDetails',
            'transPaidAmount',

            'transAllPaid',
            'transReducedAmount',
            'transPaidInterest',
            'transPaidPenaltyFee',
            'transRestInterest',
            'transRestPenaltyFee',
            'transExtraMoney',

            'transLoanID'
   ];
}
