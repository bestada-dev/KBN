<?php

namespace App\Http\Controllers\Superadmin;

use App\BenefitList;
use App\BenefitTitle;
use App\Http\Controllers\BaseController\UploadImageController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperadminLandingPageController extends UploadImageController
{
    function index(){
        return view('pages.admin.halaman-arah.index');
    }
    public function get_benefit(){
        $benefit_title = BenefitTitle::firstOrFail();
        $benefit_list = BenefitList::all();
        return view('pages.admin.halaman-arah.benefit.index', get_defined_vars());
    }

    public function update_benefit (Request $request)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'benefit.*.content' => 'required|string|max:1000',
        ]);

        // Update data utama (judul, subtitle, dan gambar)
        $benefitTitle = BenefitTitle::first(); // Ambil record pertama (anggap cuma satu)
        if (!$benefitTitle) {
            $benefitTitle = new BenefitTitle();
        }

        $benefitTitle->title = $request->title;
        $benefitTitle->sub_title = $request->sub_title;

        // Handle image jika diupload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store($this->updateDetailPhotoPath, 'public');
            $benefitTitle->image = $imagePath;
        }

        $benefitTitle->save();

        // Hapus semua data benefit lama dulu (jika tidak pakai id)
        BenefitList::truncate(); // atau ->where('title_id', $benefitTitle->id)->delete(); jika ada relasi

        // Simpan ulang data benefit dari form
        foreach ($request->benefit as $benefit) {
            BenefitList::updateOrCreate(
                ['content' => $benefit['content']], // pakai content sebagai kunci unik
                ['content' => $benefit['content']]
            );
        }
        
        return redirect()->back()->with([
            'success' => 'Data Benefit berhasil diperbarui.',
            'message' => 'Data berhasil disimpan',
            'status' => true,
        ]);
        
    }




}
