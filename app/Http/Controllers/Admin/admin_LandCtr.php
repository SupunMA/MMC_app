<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Land;


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
        $clients=User::where('users.role',0)->get(['id', 'name','NIC']);
        return view('Users.Admin.Lands.addLand',compact('clients'));
    }

    public function allLand()
    {
        return view('Users.Admin.Lands.allLands');
    }

    public function addingLand(Request $data)
    {
         $data->validate([
            'ownerID' =>'required',
            'landValue' =>'required','min:100000','max:10000000'
         ]);
        $user = Land::create($data->all());
        return redirect()->back()->with('message','successful');
        //->route('your_url_where_you_want_to_redirect');
    }
}
