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
        //$clients=User::where('users.role',0)->get(['id', 'name','NIC']);
        $landsAndClients = Land::join('users','users.id','=','lands.ownerID')
        ->where('users.role',0)->get(['users.id', 'users.name','users.NIC','lands.*']);
        //->join('table1','table1.id','=','table3.id');
        return view('Users.Admin.Lands.allLands',compact('landsAndClients'));
    }

    public function addingLand(Request $data)
    {
         $data->validate([
            'ownerID' =>'required',
            'landValue' =>'required','numeric','min:100000.00','max:99999999.99'
         ]);
        $user = Land::create($data->all());
        return redirect()->back()->with('message','Added Land!');
        //->route('your_url_where_you_want_to_redirect');
    }

    public function deleteLand($landID)
    {
        //dd($branchID);
        $delete = Land::find($landID);
        $delete->delete();
        return redirect()->back()->with('message','Deleted Land!');
    }

    public function updateLand(Request $data)
    {
        $data->validate([
            'landValue' =>'required','numeric','min:100000.0','max:99999999.99'
         ]);
        Land::where('landID', $data->landID)
        ->update(['landValue' => $data->landValue,
                'landDetails' => $data->landDetails,
                'landMap' => $data->landMap,
                'landAddress' => $data->landAddress
            ]);
        return redirect()->back()->with('message','Updated Land!');
    }




}