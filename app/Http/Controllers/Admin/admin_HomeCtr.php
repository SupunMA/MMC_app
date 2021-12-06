<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class admin_HomeCtr extends Controller
{
   //protected $task;
    
   
 //Authenticate all Admin routes
    public function __construct()
    {
        $this->middleware('checkAdmin');
       // $this->task = new branches;
    }


//Dashboard
    public function checkAdmin()
    {
        return view('Users.Admin.home');
    }

    
}
