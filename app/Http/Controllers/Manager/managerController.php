<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class managerController extends Controller
{
    public function checkManager()
    {
        return view('Users.Manager.home');
    }
}
