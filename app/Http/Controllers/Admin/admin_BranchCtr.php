<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\branches;



class admin_BranchCtr extends Controller
{
    
   
 //Authenticate all Admin routes
    public function __construct()
    {
        $this->middleware('checkAdmin');
       // $this->task = new branches;
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

    public function deleteBranch($branchID)
    {
        //dd($branchID);
        $delete = Branches::find($branchID);
        $delete->delete();
        return redirect()->back()->with('message','successful');
    }

    public function updateBranch(Request $data)
    {
        $data->validate([
            'branchName' =>'required',
            'branchAddress' =>'required'
         ]);
        branches::where('branchID', $data->branchID)
        ->update(['branchName' => $data->branchName,
                'branchAddress' => $data->branchAddress,
                'branchTP' => $data->branchTP,
                'branchLocation' => $data->branchLocation
            ]);
        return redirect()->back()->with('message','successful');
    }
    
}
