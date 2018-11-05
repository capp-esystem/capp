<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;

class HomeController extends Controller
{
    public function index()
    {
        if(Gate::allows('admin')) {
            return redirect()->route('admin');
        } else {
            return redirect()->route('student');
        }
    }
}
