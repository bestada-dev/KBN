<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

use App\Users;

class EmployeController extends Controller
{
    function index(){
        return view('pages.employe.index');
    }

    public function dataTable(Request $request)
    {
        try {

            $columns = [null, 'email', 'employe_name'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $data = [];
            $totalData = Users::count();
            $totalFiltered = $totalData;
            $posts = '';

            if (empty($request->input('search.value'))) {
                $posts = Users::whereNotIn('id', [1])->where('role_id', 3)->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            } else {

                $search = $request->input('search.value');
                $tb = Users::whereNotIn('id', [1])->where('role_id', 3)->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('employe_name', 'LIKE', "%{$search}%")
                    ->orWhere('nik', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");

                $posts = $tb->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = $tb->count();
            }

            // dd($posts);

            if (!empty($posts)) {

                $no = $start + 1;

                foreach ($posts as $a) {
                    // dd($a);
                    $d = $a;

                    $data[] = $d;
                    $d['status'] = $a->userStatus ? $a->userStatus : '-';
                }
            }
            // dd('asdasd = ', $data);
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

    public function create(Request $request)
    {
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email'
        ], [
            'required' => 'Email tidak bolek kosong.',
            'email' => 'Email yang anda masukan salah.',
            'unique' => 'Email yang anda masukan sudah ditambahkan.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = new Users();
        $user->email = $request->email;
        $user->user_status_id = 2;
        $user->company_id = Auth::user()->company_id;
        $user->company_name = Auth::user()->company_name;
        $user->role_id = 3;
        $user->is_admin = false;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat'
        ]);
    }

    // Fungsi untuk mengupdate user (Update)
    public function update(Request $request, $id)
    {
        $user = Users::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user->email = $request->email;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate'
        ]);
    }

    // Fungsi untuk menghapus user (Delete)
    public function deleteMultiple(Request $request)
    {
        // Validasi input
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id', // Ganti dengan nama tabel Anda
        ]);

        // Hapus data berdasarkan ID
        Users::destroy($request->ids);

        return response()->json(['status' => true, 'message' => 'Data berhasil dihapus.']);
    }
}
