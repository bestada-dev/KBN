<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Users;
use App\CompanyModel;

class EmployeeController extends Controller
{
    function index(){
        return view('pages.karyawan.index');
    }

    function detail($id){
        return view('pages.karyawan.detail', [
            'company' => CompanyModel::where('id', $id)->first()
        ]);
    }

    public function dataTable(Request $request)
    {
        try {
            $columns = [null];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $data = [];
            $totalData = Users::with('getEmploye')->where('user_status_id', 1)->count();
            $totalFiltered = $totalData;
            $posts = '';

            if (empty($request->input('search.value'))) {
                $query = Users::with('getEmploye')->where('user_status_id', 1)->whereNotIn('id', [1])
                    ->where('role_id', 3);

                $posts = $query->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                // With search term
                $search = $request->input('search.value');

                $query = Users::with('getEmploye')->where('user_status_id', 1)->whereNotIn('id', [1])
                    ->where('role_id', 3)
                    ->where(function ($query) use ($search) {
                        $query->where('id', 'LIKE', "%{$search}%")
                            ->orWhere('company_name', 'LIKE', "%{$search}%");
                    });

                $posts = $query->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = $query->count();
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

    public function dataTableEmployeByCompany(Request $request, $id)
    {
        try {
            $columns = [null];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $data = [];
            $totalData = Users::where('user_status_id', 1)->where('company_id', $id)->count();
            $totalFiltered = $totalData;
            $posts = '';

            if (empty($request->input('search.value'))) {
                $query = Users::where('user_status_id', 1)->whereNotIn('id', [1])
                    ->where('company_id', $id)
                    ->where('role_id', 3);

                $posts = $query->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                // With search term
                $search = $request->input('search.value');

                $query = Users::where('user_status_id', 1)->whereNotIn('id', [1])
                    ->where('company_id', $id)
                    ->where('role_id', 3)
                    ->where(function ($query) use ($search) {
                        $query->where('id', 'LIKE', "%{$search}%")
                            ->orWhere('company_name', 'LIKE', "%{$search}%");
                    });

                $posts = $query->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = $query->count();
            }

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
}
