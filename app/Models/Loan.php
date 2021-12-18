<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $primaryKey = 'loanID';
    protected $table = 'loans';

    protected $fillable = [
        'loanRate',
        'loanAmount',
        'penaltyRate',
        'loanDate',
        //'dueDate',
        'loanLandID',
        'description'
    ];
}
