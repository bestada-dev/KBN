<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\LogUsersModel;
use App\ZoningModel;
use App\ZoningStrategicLocationModel;

class ZoningController extends Controller
{
    function index(){
        return view('pages.admin.zoning.index');
    }

    function edit($id){
        // return ZoningModel::with('strategicLocation')->where('id', $id)->first();
        return view('pages.admin.zoning.edit', [
            'data_edit' => ZoningModel::with('strategicLocation')->where('id', $id)->first()
        ]);
    }

    public function dataTable(Request $request)
    {
        try {

            $columns = [null, 'zone_name'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $data = [];
            $totalData = ZoningModel::count();
            $totalFiltered = $totalData;
            $posts = '';

            if (empty($request->input('search.value'))) {
                $query = ZoningModel::query();

                $posts = $query->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

            } else {
                $search = $request->input('search.value');

                // Grouping the search conditions to enforce `role_id = 1` for all search results
                $tb = ZoningModel::where(function($query) use ($search) {
                        $query->where('id', 'LIKE', "%{$search}%")
                            ->orWhere('zone_name', 'LIKE', "%{$search}%")
                            ->orWhere('address', 'LIKE', "%{$search}%")
                            ->orWhere('link_map', 'LIKE', "%{$search}%");
                    });


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
                    $d['location'] = $a->strategicLocation ? $a->strategicLocation : '-';
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
            'zone_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'link_map' => 'required|url',
            'strategic_location.*' => 'required|string|max:255',
            'distance.*' => 'required|numeric|min:0',
            'distance_type.*' => 'required|string|in:KM,M,CM'
        ], [
            'zone_name.required' => 'zone name boleh kosong.',
            'address.required' => 'address tidak boleh kosong.',
            'link_map.required' => 'link map tidak boleh kosong.',
            // Pesan untuk array
            'strategic_location.*.required' => 'Setiap strategic location harus diisi.',
            'strategic_location.*.string' => 'Setiap strategic location harus berupa string.',
            'strategic_location.*.max' => 'Setiap strategic location tidak boleh lebih dari 255 karakter.',

            'distance.*.required' => 'Setiap distance harus diisi.',
            'distance.*.numeric' => 'Setiap distance harus berupa angka.',
            'distance.*.min' => 'Setiap distance harus bernilai minimal 0.',

            // 'distance_type.*.required' => 'Setiap distance type harus diisi.',
            // 'distance_type.*.string' => 'Setiap distance type harus berupa teks.',
            // 'distance_type.*.in' => 'Setiap distance type harus salah satu dari: KM, M, atau CM.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Save zoning data
            $zoning = ZoningModel::create([
                'zone_name' => $request->zone_name,
                'address' => $request->address,
                'link_map' => $request->link_map,
            ]);

            // Save strategic locations
            foreach ($request->strategic_location as $index => $location) {
                ZoningStrategicLocationModel::create([
                    'zoning_id' => $zoning->id,
                    'strategic_location' => $location,
                    'distance' => $request->distance[$index],
                    'distance_type' => $request->distance_type[$index],
                ]);
            }

            LogUsersModel::create([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'date' => date('Y-m-d H:i:s'),
                'type' => "ADD",
                'activity' => 'Added a new Zoning with the name: ' . $request->zone_name . ' in the Zoning Menu.',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Zoning data stored successfully.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'zone_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'link_map' => 'nullable|url',
            'strategic_location.*' => 'required|string|max:255',
            'distance.*' => 'required|numeric|min:0',
            'distance_type.*' => 'required|string|in:KM,M,CM'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Update zoning data
            $zoning = ZoningModel::findOrFail($id);

            $originalName = $zoning->zone_name;
            $zoning->update([
                'zone_name' => $request->zone_name,
                'address' => $request->address,
                'link_map' => $request->link_map,
            ]);

            // Delete old strategic locations and save new ones
            ZoningStrategicLocationModel::where('zoning_id', $id)->delete();

            foreach ($request->strategic_location as $index => $location) {
                ZoningStrategicLocationModel::create([
                    'zoning_id' => $zoning->id,
                    'strategic_location' => $location,
                    'distance' => $request->distance[$index],
                    'distance_type' => $request->distance_type[$index],
                ]);
            }

            LogUsersModel::create([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'date' => date('Y-m-d H:i:s'),
                'type' => "EDIT",
                'activity' => 'Updated Zoning "' . $originalName . '" to "' . $request->zone_name . '" in the Zoning Menu.',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Zoning data updated successfully.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }

    // Fungsi untuk menghapus user (Delete)
    public function delete(Request $request)
    {
        // Hapus data berdasarkan ID
        $getZon = ZoningModel::whereIn('id', [$request->ids])->get();
        LogUsersModel::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'date' => date('Y-m-d H:i:s'),
            'type' => "DELETED",
            'activity' => 'Deleted Category "' . $getZon[0]['zone_name'] . '" from the Category Menu.',
        ]);

        ZoningStrategicLocationModel::whereIn('zoning_id', [$request->ids])->delete();
        ZoningModel::whereIn('id', [$request->ids])->delete();


        return response()->json(['status' => true, 'message' => 'Data berhasil dihapus.']);
    }
}
