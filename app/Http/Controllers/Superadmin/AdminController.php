<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Users;
use App\LogUsersModel;
use Exception;

class AdminController extends Controller
{
    function index(){
        return view('pages.admin.superadmin.index');
    }

    public function dataTable(Request $request)
    {
        try {

            $columns = [null, 'name'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $data = [];
            $totalData = Users::count();
            $totalFiltered = $totalData;
            $posts = '';

            if (empty($request->input('search.value'))) {
                $query = Users::query();

                $status = $request->input('status_id');
                if (!empty($status) && $status != 'all') {
                    $status = $request->input('status_id');
                    $query->where('user_status_id', $status);
                }

                $posts = $query->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

            } else {
                $search = $request->input('search.value');
                $status = $request->input('status_id');

                // Grouping the search conditions to enforce `role_id = 1` for all search results
                $tb = Users::whereNotIn('id', [1])
                    ->where('role_id', '=', 1)
                    ->where(function($query) use ($search) {
                        $query->where('id', 'LIKE', "%{$search}%")
                            ->orWhere('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('phone_number', 'LIKE', "%{$search}%");
                    });

                if ($status) {
                    $tb->where('user_status_id', $status);
                }


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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8', // Panjang minimal
                'regex:/[a-z]/', // Harus ada huruf kecil
                'regex:/[A-Z]/', // Harus ada huruf besar
                'regex:/[0-9]/', // Harus ada angka
                'regex:/[@$!%*?&#]/', // Harus ada karakter spesial
            ],
            'phone_number' => 'required|string|max:15',
        ], [
            'name.required' => 'Nama tidak boleh kosong.',
            'phone_number.required' => 'Nomor tidak boleh kosong.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Email yang Anda masukan salah.',
            'email.unique' => 'Email yang Anda masukan sudah digunakan.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password harus memiliki panjang minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = new Users();
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->password = bcrypt($request->password); // Hashing password
        $user->user_status_id = $request->user_status_id == "on" ? 1 : 2;
        $user->role_id = 1;
        $user->is_admin = true;
        $user->save();

        LogUsersModel::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'date' => date('Y-m-d H:i:s'),
            'type' => "ADD",
            'activity' => 'Added a new administrator with the name: ' . $request->name . ' in the Administrator Menu.',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat',
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = Users::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        $originalName = $user->name;

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:15',
            'password' => [
                'nullable',
                'string',
                'min:8', // Panjang minimal
                'regex:/[a-z]/', // Harus ada huruf kecil
                'regex:/[A-Z]/', // Harus ada huruf besar
                'regex:/[0-9]/', // Harus ada angka
                'regex:/[@$!%*?&#]/', // Harus ada karakter spesial
            ],
        ], [
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Email yang Anda masukan salah.',
            'email.unique' => 'Email yang Anda masukan sudah digunakan.',
            'password.min' => 'Password harus memiliki panjang minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update data user
        $user->name = $request->name ?? $user->name;
        $user->phone_number = $request->phone_number ?? $user->phone_number;
        $user->email = $request->email;
        $user->user_status_id = $request->user_status_id == "on" ? 1 : 2;

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        LogUsersModel::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'date' => date('Y-m-d H:i:s'),
            'type' => "EDIT",
            'activity' => 'Updated administrator "' . $originalName . '" to "' . $request->name . '" in the Administrator Menu.',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate',
        ]);
    }

    public function changeStatus($id, Request $request) {
        // Validasi pengguna dengan ID yang diberikan
        $user = Users::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        // Validasi input
        if (!in_array($request->user_status_id, ['on', 'off'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status value.',
            ], 422);
        }

        $oldStatus = $user->user_status_id === 1 ? 'on' : 'off';

        // Ubah status
        $user->user_status_id = $request->user_status_id === "on" ? 1 : 2;
        $user->save();

        LogUsersModel::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'date' => date('Y-m-d H:i:s'),
            'type' => "STATUS_CHANGE",
            'activity' => 'Changed status of user "' . $user->name . '" from "' . $oldStatus . '" to "' . $request->user_status_id . '" in the Administrator Menu.',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully.',
            'status' => $user->user_status_id
        ]);
    }



    // Fungsi untuk menghapus user (Delete)
    public function delete(Request $request)
    {
        // $users = Users::whereIn('id', $request->ids)->get();

        $users = Users::whereIn('id', [$request->ids])->get();


        LogUsersModel::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'date' => date('Y-m-d H:i:s'),
            'type' => "DELETED",
            'activity' => 'Deleted Administrator "' . $users[0]['name'] . '" from the Administrator Menu.',
        ]);

        Users::whereIn('id', [$request->ids])->delete();

        return response()->json(['status' => true, 'message' => 'Data berhasil dihapus.']);
    }
    // public function delete(Request $request)
    // {
    //     // Hapus data berdasarkan ID
    //     Users::whereIn('id', [$request->ids])->delete();


    //     return response()->json(['status' => true, 'message' => 'Data berhasil dihapus.']);
    // }
}
