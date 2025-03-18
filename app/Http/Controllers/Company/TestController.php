<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

use App\EmployeTestModel;

class TestController extends Controller
{
    function index(){
        return view('pages.company.index');
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
            $totalData = EmployeTestModel::with(['user', 'getPelatihan'])->count();
            $totalFiltered = $totalData;
            $posts = '';

            if (empty($request->input('search.value'))) {
                $posts = EmployeTestModel::with(['user', 'getPelatihan'])->where('created_by', Auth::user()->id)->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            } else {

                $search = $request->input('search.value');
                $tb = EmployeTestModel::with(['user', 'getPelatihan'])
                    ->where('created_by', Auth::user()->id)
                    ->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kategori', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%");

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
