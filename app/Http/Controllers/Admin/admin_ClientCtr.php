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

        $branches=branches::all('branchName', 'branchID');
        $clients = User::join('branches','branches.branchID','=','users.refBranch')
        ->where('users.role',0)->get();
        //->join('table1','table1.id','=','table3.id');
        return view('Users.Admin.Clients.allClients',compact('clients','branches'));
    }

    public function deleteClient($userID)
    {
        //dd($branchID);
        $delete = User::find($userID);
        $delete->delete();
        return redirect()->back()->with('message','Deleted Customer!');
    }

    public function updateClient(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // 'email' => ['string', 'email', 'max:255', 'unique:users'],
            //'password' => ['required', 'string', 'min:8', 'confirmed'],
            'address' => ['string','required'],
            'mobile' =>['string'],
            'NIC'=>['integer','unique:users,id'], //can save same value according to user id
            'refBranch'=>['required']
        ]);


        User::where('id', $request->id)
        ->update([
                    'name' => $request->name,
                    //'email' => $request->email,
                    //'password' => \Hash::make($request->password),
                    'mobile' => $request->mobile,
                    'address' => $request->address,
                    'NIC' => $request->NIC,
                    'fileName' => $request->fileName,
                    'photo' => $request->photo,
                    'userMap' => $request->userMap,
                    'refBranch' => $request->refBranch
                ]);

        return redirect()->back()->with('message','Updated Customer!');

    }
}