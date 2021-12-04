<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\branches;

class adminController extends Controller
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

//Client

    public function addClient()
    {
        $branches=branches::all('branchName', 'id');
        return view('Users.Admin.Clients.addClient',compact('branches'));
    }

    public function allClient()
    {
        return view('Users.Admin.Clients.allClients');
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

//Loan

    public function addLoan()
    {
        return view('Users.Admin.Loans.addLoan');
    }

    public function allLoan()
    {
        return view('Users.Admin.Loans.allLoans');
    }

//Branch

    public function addBranch()
    {
        return view('Users.Admin.Branches.AddNewBranch');
    }
    
    public function allBranch()
    {
        $bdata = branches::all();
        return view('Users.Admin.Branches.AllBranches',compact('bdata'));
    }

    public function addingBranch(Request $data)
    {
         $data->validate([
            'branchName' =>'required',
            'branchAddress' =>'required'
         ]);
        $user = Branches::create($data->all());
        return redirect()->back()->with('message','successful');
        //->route('your_url_where_you_want_to_redirect');
    }

    public function deleteBranch($id)
    {
        $delete = Branches::find($id);
        $delete->delete();
        return redirect()->back()->with('message','successful');
    }

    public function updateBranch(Request $data)
    {
        $data->validate([
            'branchName' =>'required',
            'branchAddress' =>'required'
         ]);
        branches::where('id', $data->id)
        ->update(['branchName' => $data->branchName,
                'branchAddress' => $data->branchAddress,
                'branchTP' => $data->branchTP,
                'branchLocation' => $data->branchLocation
            ]);
        return redirect()->back()->with('message','successful');
    }
    
}
