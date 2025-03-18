<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contact;

class ContactController extends Controller
{
    function index(){
        return view('pages.admin.contacts.index');
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
            $totalData = Contact::count();
            $totalFiltered = $totalData;
            $posts = '';

            if (empty($request->input('search.value'))) {
                $posts = Contact::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            } else {

                $search = $request->input('search.value');
                $tb = Contact::where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('message', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");

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
                    $d['sent_at'] = $a->created_at ? $a->created_at : '-';
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


    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:500',
        ]);

        Contact::create($request->all());

        return response()->json(['status' => true, 'message' => 'Message sent successfully.']);
    }

    public function delete(Request $request)
    {
        // Hapus data berdasarkan ID
        Contact::whereIn('id', $request->ids)->delete();

        return response()->json(['status' => true, 'message' => 'Data berhasil dihapus.']);
    }
}
