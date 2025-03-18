<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuperadminLandingPageController extends Controller
{
    function index(){
        return view('pages.admin.halaman-arah.index');
    }

}
