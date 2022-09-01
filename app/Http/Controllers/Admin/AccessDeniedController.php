<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccessDeniedController extends Controller
{
    
    /**
    * Show access denied page
    * @return view 
    */
    public function index(){
        return view('admin/access-denied');
    }
}
