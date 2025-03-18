<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

use App\PelatihanSayaModel;

class PengembanganDiriController extends Controller
{
    function index(){
        return view('pages.vendor.pengembanganDiri.index');
    }

    function create(){
        return view('pages.vendor.pengembanganDiri.create');
    }

    function update($id){
        return view('pages.vendor.pengembanganDiri.update', [
            'edit_data' => PelatihanSayaModel::where('id', $id)->first()
        ]);
    }

    function detail($id){
        return view('pages.vendor.pengembanganDiri.detail', [
            'edit_data' => PelatihanSayaModel::where('id', $id)->first()
        ]);
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
            $totalData = PelatihanSayaModel::where('kategori', 'Pengembangan')->count();
            $totalFiltered = $totalData;
            $posts = '';

            if (empty($request->input('search.value'))) {
                $posts = PelatihanSayaModel::where('kategori', 'Pengembangan')
                    ->whereNotIn('id', [1])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

            } else {
                $search = $request->input('search.value');

                // Grouping search conditions to enforce `kategori = 'Pengembangan'` for all search results
                $tb = PelatihanSayaModel::where('kategori', 'Pengembangan')
                    ->whereNotIn('id', [1])
                    ->where(function($query) use ($search) {
                        $query->where('id', 'LIKE', "%{$search}%")
                            ->orWhere('id_pelatihan', 'LIKE', "%{$search}%")
                            ->orWhere('judul_pelatihan', 'LIKE', "%{$search}%");
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
                    // $d['status'] = $a->userStatus ? $a->userStatus : '-';
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
        // return $request->all();
        // Validasi input
        $validator = Validator::make($request->all(), [
            'judul_pelatihan' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Maks 5MB untuk gambar
            'vidio' => 'nullable|mimes:mp4,mov,avi|max:1048576', // Maks 1GB untuk video
            'deskripsi' => 'required|string',
        ], [
            'required' => ':attribute tidak boleh kosong.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berupa file dengan format: :values.',
            'max.file' => 'Ukuran file :attribute tidak boleh lebih dari :max kilobyte.',
            'url' => ':attribute harus berupa URL yang valid.',
            'required_if' => ':attribute wajib diisi jika jenis pelatihan adalah online.',
        ], [
            'judul_pelatihan' => 'Judul Pelatihan',
            'foto' => 'Gambar',
            'vidio' => 'Video',
            'deskripsi' => 'Deskripsi Pelatihan',
        ]);

        // Menyimpan file gambar dan video
        $fotoPath = null;
        $vidioPath = null;

        if ($request->hasFile('foto')) {
            // Simpan gambar ke dalam storage di folder 'public/pengembangan/foto'
            $fotoPath = $request->file('foto')->store('pengembangan/foto', 'public');
        }

        if ($request->hasFile('vidio')) {
            // Simpan video ke dalam storage di folder 'public/pengembangan/vidio'
            $vidioPath = $request->file('vidio')->store('pengembangan/vidio', 'public');
        }

        // Generate id_pelatihan berdasarkan pola 0001, 0002, dst.
        $lastPengembangan = PelatihanSayaModel::where('kategori', 'Pengembangan')->orderBy('id', 'desc')->first();
        $newIdPengembangan = $lastPengembangan ? sprintf('%04d', (int)substr($lastPengembangan->id_pelatihan, -4) + 1) : '0001';

        // Buat data pelatihan baru
        $pengembangan = PelatihanSayaModel::create([
            'id_pelatihan' => $newIdPengembangan,
            'judul_pelatihan' => $request->judul_pelatihan,
            'foto' => $fotoPath,
            'vidio' => $vidioPath,
            'deskripsi' => $request->deskripsi,
            'created_by' => Auth::user()->id,
            'company_id' => Auth::user()->company_id,
            'kategori' => "Pengembangan",
        ]);

        return response()->json([
            'status' => true,
            'message' => 'pengembangan berhasil dibuat.',
            'data' => $pengembangan,
        ], 200);
    }


    public function edit(Request $request, $id)
    {
        // Ambil data pelatihan berdasarkan ID
        $pengembangan = PelatihanSayaModel::findOrFail($id);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'judul_pelatihan' => 'required',
            'foto' => $pengembangan->foto ? 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120' : 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Jika ada foto sebelumnya, tidak wajib, jika tidak ada wajib diisi
            'vidio' => 'nullable|mimes:mp4,mov,avi|max:1048576', // Maks 1GB untuk video
            'deskripsi' => 'required|string',
        ], [
            'required' => ':attribute tidak boleh kosong.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berupa file dengan format: :values.',
            'max.file' => 'Ukuran file :attribute tidak boleh lebih dari :max kilobyte.',
            'url' => ':attribute harus berupa URL yang valid.',
            'required_if' => ':attribute wajib diisi jika jenis pelatihan adalah online.',
        ], [
            'judul_pelatihan' => 'Judul Pelatihan',
            'foto' => 'Gambar',
            'vidio' => 'Video',
            'deskripsi' => 'Deskripsi Pelatihan',
        ]);

        // Jika validasi gagal, kembalikan dengan pesan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Menyimpan file gambar dan video baru jika ada, atau tetap menggunakan yang lama
        if ($request->hasFile('foto')) {
            // Hapus gambar lama jika ada
            if ($pengembangan->foto) {
                Storage::disk('public')->delete($pengembangan->foto);
            }
            // Simpan gambar baru ke dalam storage di folder 'public/pelatihan/foto'
            $pengembangan->foto = $request->file('foto')->store('pelatihan/foto', 'public');
        }

        if ($request->hasFile('vidio')) {
            // Hapus video lama jika ada
            if ($pengembangan->vidio) {
                Storage::disk('public')->delete($pengembangan->vidio);
            }
            // Simpan video baru ke dalam storage di folder 'public/pelatihan/vidio'
            $pengembangan->vidio = $request->file('vidio')->store('pelatihan/vidio', 'public');
        }

        // Update data pelatihan
        $pengembangan->update([
            'judul_pelatihan' => $request->judul_pelatihan,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'pengembangan berhasil ubah.',
            'data' => $pengembangan,
        ], 200);
    }


    // Fungsi untuk menghapus user (Delete)
    public function delete(Request $request)
    {
        PelatihanSayaModel::whereIn('id', $request->ids)->delete();

        return response()->json(['status' => true, 'message' => 'Data berhasil dihapus.']);
    }
}
