<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

use App\CompanyModel;
use App\PelatihanSayaModel;

class JadwalPelatihanController extends Controller
{
    function index(){

        return view('pages.admin.jadwal_pelatihan.index');
    }

    function detailByCompany(){
        // return PelatihanSayaModel::with('aksesPelatihan')->get();
        return view('pages.admin.jadwal_pelatihan.detailByCompany');
    }

    public function getDetail($id) {
        $data = PelatihanSayaModel::with('aksesPelatihan')->where('id', $id)->first();
        return response()->json($data);
    }

    public function dataTable(Request $request)
    {
        try {
            $columns = [null, 'id_pelatihan', 'judul_pelatihan'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $data = [];

            // Count only companies with `getPelatihan` data > 0
            $totalData = CompanyModel::whereHas('getPelatihan')->count();
            $totalFiltered = $totalData;
            $posts = '';

            if (empty($request->input('search.value'))) {
                // Filter companies to include only those with at least one `getPelatihan` entry
                $posts = CompanyModel::whereHas('getPelatihan')
                    ->with('getPelatihan')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

            } else {
                $search = $request->input('search.value');

                // Filter and search in `CompanyModel` and related `getPelatihan`
                $tb = CompanyModel::whereHas('getPelatihan')
                    ->with(['getPelatihan' => function ($query) use ($search) {
                        $query->where('id_pelatihan', 'LIKE', "%{$search}%")
                            ->orWhere('judul_pelatihan', 'LIKE', "%{$search}%");
                    }])
                    ->where(function ($query) use ($search) {
                        $query->where('id', 'LIKE', "%{$search}%")
                            ->orWhere('name', 'LIKE', "%{$search}%");
                    });

                $posts = $tb->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = $tb->count();
            }

            if (!empty($posts)) {
                $no = $start + 1;
                foreach ($posts as $a) {
                    $d = $a;
                    $data[] = $d;
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

    public function dataTableByCompany(Request $request, $id)
    {
        try {
            $columns = [null, 'id_pelatihan', 'judul_pelatihan'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $data = [];
            $totalData = PelatihanSayaModel::where('company_id', $id)->count();
            $totalFiltered = $totalData;
            $posts = '';

            // Initialize the query
            $query = PelatihanSayaModel::where('company_id', $id);

            if (!empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where(function($query) use ($search) {
                    $query->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('id_pelatihan', 'LIKE', "%{$search}%")
                        ->orWhere('judul_pelatihan', 'LIKE', "%{$search}%");
                });
            }



            // return $tanggalFilter;

            // Filter by category if set
            if ($categoryId = $request->input('pelatihan_id')) {
                $query->where('kategori', $categoryId); // Assuming 'type' is the category column
            }

            // Filter by status if set
            if ($status = $request->input('status_id')) {
                $query->where('status_pelatihan', $status);
            }

            $tanggalFilter = $request->tanggal; // Misalnya: 20/11/2024

            if (!empty($tanggalFilter)) {
                // Parsing tanggal dengan format d/m/Y dan mengonversinya ke format Y-d-m
                $tanggalFilter = Carbon::createFromFormat('d/m/Y', $tanggalFilter)->format('Y-m-d');
            }

            // return $tanggalFilter;

            if ($tanggalFilter) {
                $query->where('tanggal_mulai', $tanggalFilter);
            }

            // Apply offset and limit for pagination
            $posts = $query->offset($start)->limit($limit)->orderBy($order, $dir)->get();
            $totalFiltered = $query->count();

            if (!empty($posts)) {
                foreach ($posts as $a) {
                    $data[] = $a;
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

    function updateJadwal(Request $request) {
        try {

            $cekPelatihan = PelatihanSayaModel::where('id', $request->id)->first();

            if (empty($cekPelatihan)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Tidak ditemukan'
                ], 404);
            }

            $tanggalMulai = new \DateTime($request->tanggal_mulai);
            $tanggalAkhir = new \DateTime($request->tanggal_akhir);
            $tanggalSekarang = new \DateTime();

            $hari = $tanggalMulai->diff($tanggalAkhir)->days + 1;

            if ($tanggalSekarang < $tanggalMulai) {
                $statusPelatihan = 'Belum Dimulai';
            } elseif ($tanggalSekarang >= $tanggalMulai && $tanggalSekarang <= $tanggalAkhir) {
                $statusPelatihan = 'Sedang Berlangsung';
            } else {
                $statusPelatihan = 'Selesai';
            }

            $cekPelatihan->update([
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir,
                'hari' => $hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_akhir' => $request->jam_akhir,
                'status_pelatihan' => $statusPelatihan,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $cekPelatihan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


}
