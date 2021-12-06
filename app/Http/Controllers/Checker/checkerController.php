<?php

namespace App\Http\Controllers\Checker;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class checkerController extends Controller
{
    public function checkChecker()
    {
        return view('Users.Checker.home');
    }
}
