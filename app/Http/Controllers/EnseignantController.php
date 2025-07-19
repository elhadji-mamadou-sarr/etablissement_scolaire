<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnseignantController extends Controller
{
    public function dashboard()
    {
        return view('enseignant.dashboard');
    }
}
