<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class admin_LandCtr extends Controller
{
   //protected $task;
    
   
 //Authenticate all Admin routes
    public function __construct()
    {
        $this->middleware('checkAdmin');
       // $this->task = new branches;
    }


//Land

    public function addLand()
    {
        return view('Users.Admin.Lands.addLand');
    }

    public function allLand()
    {
        return view('Users.Admin.Lands.allLands');
    }

}
