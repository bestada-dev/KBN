<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HalamanArahController extends Controller
{
    function index(){
        return view('pages.admin.halaman-arah.index');
    }

}
