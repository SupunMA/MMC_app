<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Land;


class userController extends Controller
{
    public function checkUser()
    {

        
        $loanData = Land::join('users','users.id','=','lands.ownerID')
        ->join('loans','loans.loanLandID','=','lands.landID')
        ->where('users.id',Auth::user()->id)->get(['users.*','lands.*','loans.*']);

        $transactionData = Land::join('users','users.id','=','lands.ownerID')
        ->join('loans','loans.loanLandID','=','lands.landID')
        ->join('transactions','transactions.transLoanID','=','lands.landID')
        ->where('users.id',Auth::user()->id)->get(['users.*','lands.*','loans.*','transactions.*']);
        
        $countTransRows=$transactionData->count();
        //dd($clients);
        return view('Users.User.home',compact('loanData','transactionData','countTransRows'));
    }
}
