<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\CategoryModel;
use App\LogUsersModel;
use Exception;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CategoryController extends Controller
{
    function index(){
        return view('pages.admin.category.index');
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
            $totalData = CategoryModel::with('getProperty')->count();
            $totalFiltered = $totalData;
            $posts = '';

            if (empty($request->input('search.value'))) {
                $query = CategoryModel::with('getProperty');

                $posts = $query->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

            } else {
                $search = $request->input('search.value');

                // Grouping the search conditions to enforce `role_id = 1` for all search results
                $tb = CategoryModel::with('getProperty')->whereNotIn('id', [1])
                    ->where('role_id', '=', 1)
                    ->where(function($query) use ($search) {
                        $query->where('id', 'LIKE', "%{$search}%")
                            ->orWhere('name', 'LIKE', "%{$search}%");
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ], [
            'name.required' => 'name tidak boleh kosong.',
            'icon.required' => 'Icon tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('public/icons');
        }

        $category = CategoryModel::create([
            'name' => $request->input('name'),
            'icon' => $iconPath ?? null, // Menyimpan path file ikon jika ada
        ]);

        LogUsersModel::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'date' => date('Y-m-d H:i:s'),
            'type' => "ADD",
            'activity' => 'Added a new Category with the name: ' . $request->input('name') . ' in the Category Menu.',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat',
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048', // Ikon opsional pada update
        ], [
            'name.required' => 'name boleh kosong.',
            'icon.required' => 'Icon tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $category = CategoryModel::findOrFail($id);

        $originalName = $category->name;

        if ($request->hasFile('icon')) {
            if ($category->icon) {
                Storage::delete($category->icon);
            }

            $iconPath = $request->file('icon')->store('public/icons');
            $category->icon = $iconPath;
        }

        $category->name = $request->input('name');
        $category->save();

        LogUsersModel::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'date' => date('Y-m-d H:i:s'),
            'type' => "EDIT",
            'activity' => 'Updated Category "' . $originalName . '" to "' . $request->input('name') . '" in the Category Menu.',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat',
        ]);
    }

    public function delete(Request $request)
    {
        // Hapus data berdasarkan ID
        $getCategory = CategoryModel::whereIn('id', [$request->ids])->get();
        CategoryModel::whereIn('id', [$request->ids])->delete();

        LogUsersModel::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'date' => date('Y-m-d H:i:s'),
            'type' => "DELETED",
            'activity' => 'Deleted Category "' . $getCategory[0]['name'] . '" from the Category Menu.',
        ]);

        return response()->json(['status' => true, 'message' => 'Data berhasil dihapus.']);
    }
}
