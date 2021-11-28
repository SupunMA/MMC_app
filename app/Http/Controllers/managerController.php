<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class managerController extends Controller
{
    public function checkManager()
    {
        return view('Users.Manager.home');
    }
}
