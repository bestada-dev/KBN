<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PanduanController extends Controller
{
    public function index()
    {
        $filePath = public_path('manual_book/User_Guide_KBN.pdf');

        // Cek apakah file ada
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File buku panduan tidak ditemukan.');
        }

        // Return file untuk didownload
        return response()->download($filePath, 'User_Guide_KBN.pdf');
    }
}
