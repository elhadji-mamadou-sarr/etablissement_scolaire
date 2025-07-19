<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EleveParentController extends Controller
{
    public function dashboard()
    {
        return view('eleve-parent.dashboard');
    }
}
