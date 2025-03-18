<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

use App\AksesPelatihanSettingModel;

class TrainingController extends Controller
{
    function index(){
        return view('pages.company.pelatihanSaya.index');
    }

    function detail($id){
        // return AksesPelatihanSettingModel::with('getPelatihanDanPengembangan')->where('id', $id)->first();
        return view('pages.company.pelatihanSaya.detail', [
            'edit_data' => AksesPelatihanSettingModel::where('id', $id)->first()
        ]);
    }

    public function dataTable(Request $request)
    {
        try {
            $columns = [null, 'code'];
            $limit = $request->input('length');  // Jumlah data per halaman
        $start = $request->input('start');   // Offset (mulai data)
        $orderColumn = $columns[$request->input('order.0.column')] ?? 'id'; // Kolom yang digunakan untuk sorting
        $dir = $request->input('order.0.dir') ?? 'DESC'; // Arah pengurutan

        $data = [];

        // Handle search and filter functionality
        $search = $request->input('search.value');  // Mencari berdasarkan pencarian
        $kategori_id = $request->input('kategori_id'); // Filter kategori jika ada

        // Query awal untuk mengambil data
        $query = AksesPelatihanSettingModel::with(['getPelatihanDanPengembangan', 'company'])
                        ->where('type', 'Pelatihan')
                        ->where('company_id', Auth::user()->company_id);

        // Tambahkan filter pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search, $kategori_id) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhereHas('getPelatihanDanPengembangan', function ($q) use ($search) {
                      $q->where('judul_pelatihan', 'LIKE', "%{$search}%");
                  });

                // Jika ada filter kategori
            });
        }

        if (!empty($kategori_id) && $kategori_id !== 'undefined') {
            $query->whereHas('getPelatihanDanPengembangan', function ($q) use ($kategori_id) {
                $q->where('type', 'LIKE', "%{$kategori_id}%");
            });
        }

        // Hitung total data setelah filter untuk pagination
        $totalFiltered = $query->count();

        // Ambil data dengan pagination
        $posts = $query->offset($start)
                       ->limit($limit)
                       ->orderBy($orderColumn, $dir)
                       ->get();

        // Menyusun array data untuk ditampilkan di DataTables
        foreach ($posts as $a) {
            $data[] = $a;
        }

        // Mengembalikan response dalam format JSON untuk DataTables
        $json_data = [
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalFiltered),  // Jumlah total setelah filter
            "recordsFiltered" => intval($totalFiltered),  // Jumlah data yang sudah difilter
            "data" => $data
        ];

        return response()->json($json_data);

        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
