<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

use App\Certificate;

class CertificateController extends Controller
{
    function index(){
        return view('pages.company.certificate.index');
    }

    function detail($id){
        // return AksesPelatihanSettingModel::with('getPelatihanDanPengembangan')->where('id', $id)->first();
        // home_photos/672dc08415037.1731051652.png
        // dd(Certificate::with(['pelatihan.getVendor', 'user'])->find($id));
        return view('pages.company.certificate.detail', [
            'data' => Certificate::with(['pelatihan.getVendor', 'user.company'])->find($id)
        ]);
    }

    public function dataTable(Request $request)
    {
        try {
            $columns = [null, ];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $data = [];

            // Total data count for pagination
            $totalData = Certificate::with(['user', 'pelatihan'])
            ->whereHas('user', function ($query) {
                $query->where('company_id', Auth::user()->company_id);
            })
                            ->count();
            $totalFiltered = $totalData;
            $posts = '';

            // Handle search functionality
            $search = $request->input('search.value');
            $kategori_pelatihan = $request->input('kategori_pelatihan'); // Get the type filter value
            
            $query = Certificate::with(['user', 'pelatihan'])
            ->whereHas('user', function ($query) {
                $query->where('company_id', Auth::user()->company_id);
            });

            if (!empty($search)) {
                $query->where(function ($q) use ($search, $kategori_pelatihan) {
                    $q->where('id', 'LIKE', "%{$search}%")
                      ->orWhere('certificate_number', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('admin_name', 'LIKE', "%{$search}%");
                        })
                      ->orWhereHas('pelatihan', function ($q) use ($search) {
                          $q->where('judul_pelatihan', 'LIKE', "%{$search}%");
                      });

                 
                });
            }

               // Include the type filter if it's set
            if (!empty($kategori_pelatihan)) {
                $query->where(function ($q) use ($search, $kategori_pelatihan) {
                    $q->orWhereHas('pelatihan', function ($q) use ($kategori_pelatihan) {
                        $q->where('kategori', 'LIKE', "%{$kategori_pelatihan}%");
                    });
                });
            }

            // Get filtered results with pagination
            $posts = $query->offset($start)
                           ->limit($limit)
                           ->orderBy($order, $dir)
                           ->get();

            $totalFiltered = $query->count(); // Update the total filtered count

            // Populate data array
            foreach ($posts as $a) {
                $data[] = $a;
            }

            // Prepare JSON response
            $json_data = [
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data
            ];

            return response()->json($json_data);

        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
