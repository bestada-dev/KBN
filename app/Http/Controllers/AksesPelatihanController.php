<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use DB;
use Exception; // Import the Exception class

use App\AksesPelatihanSettingModel; // Import Answer model
use App\PelatihanSayaModel;
use App\CompanyModel;

class AksesPelatihanController extends Controller
{
    function index(){
        return view('pages.admin.akses_pelatihan.index', [
            'get_perusahaan' => CompanyModel::get()
        ]);
    }

    public function getJudulPelatihan(Request $request)
    {
        $pelatihan = $request->input('pelatihan');

        // Daftar judul pelatihan
        // $judulPelatihan = [];

        // if ($pelatihan == 'Pelatihan') {
        //     $judulPelatihan = [
        //         ['id' => 1, 'nama' => 'Pelatihan Kepemimpinan'],
        //         ['id' => 2, 'nama' => 'Pelatihan Manajemen Proyek'],
        //     ];
        // } elseif ($pelatihan == 'Pengembangan') {
        //     $judulPelatihan = [
        //         ['id' => 3, 'nama' => 'Pengembangan Diri dan Soft Skill'],
        //         ['id' => 4, 'nama' => 'Komunikasi Efektif'],
        //     ];
        // }

        $judulPelatihan = PelatihanSayaModel::where('kategori', $pelatihan)->get();

        // Mengembalikan respons sebagai JSON
        return response()->json($judulPelatihan);
    }


    public function dataTable(Request $request)
    {
        try {
            $columns = [null, 'code', 'judul_pelatihan_id'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $data = [];
            $totalData = AksesPelatihanSettingModel::count();
            $totalFiltered = $totalData;
            $posts = '';

            // Ambil nilai filter dari request
            // $search = $request->input('search.value');
            // $pelatihan = $request->input('pelatihan');

            // Filter query utama
            // $query = AksesPelatihanSettingModel::when($search, function ($q) use ($search) {
            //         $q->where('id', 'LIKE', "%{$search}%")
            //         ->orWhere('code', 'LIKE', "%{$search}%")
            //         ->orWhere('type', 'LIKE', "%{$pelatihan}%");
            //     });

            // $totalFiltered = $query->count();

            // // Ambil data dengan batas dan urutan
            // $posts = $query->offset($start)
            //     ->limit($limit)
            //     ->orderBy($order, $dir)
            //     ->get();

            if (empty($request->input('search.value'))) {
                $query = AksesPelatihanSettingModel::query();

                $kategori = $request->input('kategori_id');
                if (!empty($kategori) && $kategori != 'all') {
                    $kategori = $request->input('kategori_id');
                    $query->where('type', $kategori);
                }

                $posts = $query->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

            } else {
                $search = $request->input('search.value');
                $kategori = $request->input('kategori_id');

                // Grouping the search conditions to enforce `role_id = 1` for all search results
                $tb = AksesPelatihanSettingModel::where(function($query) use ($search) {
                        $query->where('id', 'LIKE', "%{$search}%")
                            ->orWhere('code', 'LIKE', "%{$search}%");
                    });

                if ($kategori) {
                    $tb->where('type', $kategori);
                }


                $posts = $tb->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = $tb->count();
            }

            // Format hasil data
            if (!empty($posts)) {
                $no = $start + 1;
                foreach ($posts as $a) {
                    $d = $a;
                    $data[] = $d;
                    $d['pelatihan'] = $a->getPelatihanDanPengembangan ? $a->getPelatihanDanPengembangan : '-';
                    $d['company'] = $a->company ? $a->company : '-';
                }
            }

            $json_data = [
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data
            ];

            return response()->json($json_data);

        } catch (Exception $e) {
            return ____error([__FUNCTION__, 'Line : '.$e->getLine(). ' Message : '.$e->getMessage()]);
        }
    }


    public function store(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'judul_pelatihan_id' => 'required|string',
            'company_id' => 'required|string',
            'employe_total' => 'required|string',
        ]);

        try {

            $exists = AksesPelatihanSettingModel::where('company_id', $request->company_id)
                ->where('judul_pelatihan_id', $request->judul_pelatihan_id)
                ->where('type', $request->type)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kombinasi ini sudah ada. Silakan periksa kembali data Anda.'
                ], 422);
            }

            $lastAkses = AksesPelatihanSettingModel::orderBy('id', 'desc')->first();

            if ($lastAkses) {
                $lastAksesCode = (int)substr($lastAkses->pelatihan, -4);
                $nextSequenceNumber = $lastAksesCode + 1;
            } else {
                $nextSequenceNumber = 1;
            }

            $pelatihanAksesCode = str_pad($nextSequenceNumber, 4, '0', STR_PAD_LEFT);
            $test = AksesPelatihanSettingModel::create([
                'code' =>$pelatihanAksesCode,
                'type' => $request->type,
                'judul_pelatihan_id' => $request->judul_pelatihan_id,
                'company_id' => $request->company_id,
                'employe_total' => $request->employe_total,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dibuat'
            ]);
        } catch (Exception $e) {
            // Log the exception message
            \Log::error('Error saving test data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        // Validation for incoming data
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'judul_pelatihan_id' => 'required|string',
            'company_id' => 'required|string',
            'employe_total' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $test = AksesPelatihanSettingModel::findOrFail($id);

            // Update the test details
            $test->update([
                'type' => $request->type,
                'judul_pelatihan_id' => $request->judul_pelatihan_id,
                'company_id' => $request->company_id,
                'employe_total' => $request->employe_total,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil diubah'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        } catch (Exception $e) {
            \Log::error('Error updating test data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    // Fungsi untuk menghapus user (Delete)
    public function delete(Request $request)
    {
        // return $request->all();
        // Validasi input
        // $request->validate([
        //     'ids' => 'required|array',
        //     'ids.*' => 'exists:users,id', // Ganti dengan nama tabel Anda
        // ]);

        // Hapus data berdasarkan ID
        AksesPelatihanSettingModel::whereIn('id', $request->ids)->delete();

        return response()->json(['status' => true, 'message' => 'Data berhasil dihapus.']);
    }
}
