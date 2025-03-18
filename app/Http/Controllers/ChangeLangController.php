<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChangeLangController extends Controller
{
    public function changeLanguage(Request $request)
    {
        $locale = $request->input('locale');
        session()->put('locale', $locale);
        app()->setLocale($locale);
        // dd($locale\);
        return redirect()->back();
    }
}
