<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardContrtoller extends Controller
{
    //


    public function index(){
         

        return  view('admin.index3',["data"=>['sss']]);
    }
}
