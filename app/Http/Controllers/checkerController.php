<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class checkerController extends Controller
{
    public function checkChecker()
    {
        return view('Users.Checker.home');
    }
}
