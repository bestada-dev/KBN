<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\MessageModel;

class MessageController extends Controller
{
    function index(){
        return view('pages.admin.message.index');
    }

    public function dataTable(Request $request)
    {
        try {
            $columns = [null, 'first_name', 'last_name'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $tanggalFilter = $request->input('tanggal');

            if (!empty($tanggalFilter)) {
                $tanggalFilter = Carbon::createFromFormat('d/m/Y', $tanggalFilter)->format('Y-m-d');
            }

            $data = [];
            $totalData = MessageModel::count();
            $totalFiltered = $totalData;

            $query = MessageModel::query();

            // Handle search functionality
            $search = $request->input('search.value');
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone_number', 'LIKE', "%{$search}%")
                    ->orWhere('message', 'LIKE', "%{$search}%");
                });
            }

            if (!empty($tanggalFilter)) {
                $query->where('created_at', $tanggalFilter);
            }

            // Get the filtered results with pagination
            $posts = $query->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();

            // Update the total filtered count
            $totalFiltered = $query->count();

            // Populate the data array
            foreach ($posts as $a) {
                $data[] = $a;
            }

            // Prepare and return the JSON response
            $json_data = [
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data
            ];

            return response()->json($json_data);

        } catch (Exception $e) {
            return response()->json([
                'error' => [
                    'function' => __FUNCTION__,
                    'line' => $e->getLine(),
                    'message' => $e->getMessage()
                ]
            ]);
        }
    }
}
