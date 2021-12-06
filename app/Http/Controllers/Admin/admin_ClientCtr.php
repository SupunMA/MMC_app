<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\branches;
use App\Models\User;



class admin_ClientCtr extends Controller
{
    
   
 //Authenticate all Admin routes
    public function __construct()
    {
        $this->middleware('checkAdmin');
       // $this->task = new branches;
    }


    //Client

    public function addClient()
    {
        $branches=branches::all('branchName', 'branchID');
        return view('Users.Admin.Clients.addClient',compact('branches'));
    }

    public function allClient()
    {
        //$clients=User::where('role',0)->get();
        
        $clients = User::join('branches','branches.branchID','=','users.refBranch')
        ->where('users.role',0)->get();
        //->join('table1','table1.id','=','table3.id');
        return view('Users.Admin.Clients.allClients',compact('clients'));
    }
    
}
