<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

use App\PelatihanSayaModel;

class PelatihanSayaController extends Controller
{
    function index(){
        return view('pages.vendor.pelatihanSaya.index');
    }

    function create(){
        return view('pages.vendor.pelatihanSaya.create');
    }

    function update($id){
        return view('pages.vendor.pelatihanSaya.update', [
            'edit_data' => PelatihanSayaModel::where('id', $id)->first()
        ]);
    }

    function detail($id){
        return view('pages.vendor.pelatihanSaya.detail', [
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
            $totalData = PelatihanSayaModel::count();
            $totalFiltered = $totalData;
            $posts = '';

            if (empty($request->input('search.value'))) {
                $query = PelatihanSayaModel::query();

                $type = $request->input('type');
                if (!empty($type) && $type != 'all') {
                    $type = $request->input('type');
                    $query->where('type', $type);
                }

                $posts = $query->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

            } else {
                $search = $request->input('search.value');
                $type = $request->input('type');

                \Log::info("Search Term: {$search}");
                \Log::info("Filter Type: {$type}");

                // Grouping search conditions to enforce `kategori = 'Pelatihan'` for all search results
                $tb = PelatihanSayaModel::where(function($query) use ($search) {
                        $query->where('id', 'LIKE', "%{$search}%")
                            ->orWhere('id_pelatihan', 'LIKE', "%{$search}%")
                            ->orWhere('judul_pelatihan', 'LIKE', "%{$search}%");
                    });

                if ($type) {
                    $tb->where('type', $type);
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
        $validator = Validator::make($request->all(), [
            'judul_pelatihan' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Maks 5MB untuk gambar
            'vidio' => 'nullable|mimes:mp4,mov,avi|max:1048576', // Maks 1GB untuk video
            'type' => 'required|in:online,offline', // Wajib dan harus bernilai 'online' atau 'offline'
            'link' => 'nullable|required_if:type,online|url', // Link wajib diisi jika type = online
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
            'type' => 'Jenis Pelatihan',
            'link' => 'Link',
            'deskripsi' => 'Deskripsi Pelatihan',
        ]);

        // return $request->all();
        // Menyimpan file gambar dan video
        $fotoPath = null;
        $vidioPath = null;

        if ($request->hasFile('foto')) {
            // Simpan gambar ke dalam storage di folder 'public/pelatihan/foto'
            $fotoPath = $request->file('foto')->store('pelatihan/foto', 'public');
        }

        if ($request->hasFile('vidio')) {
            // Simpan video ke dalam storage di folder 'public/pelatihan/vidio'
            $vidioPath = $request->file('vidio')->store('pelatihan/vidio', 'public');
        }

        // Generate id_pelatihan berdasarkan pola 0001, 0002, dst.
        $lastPelatihan = PelatihanSayaModel::orderBy('id', 'desc')->first();
        $newIdPelatihan = $lastPelatihan ? sprintf('%04d', (int)substr($lastPelatihan->id_pelatihan, -4) + 1) : '0001';

        $pelatihan = PelatihanSayaModel::create([
            'id_pelatihan' => $newIdPelatihan,
            'judul_pelatihan' => $request->judul_pelatihan,
            'foto' => $fotoPath,
            'vidio' => $vidioPath,
            'type' => $request->type ? $request->type : 'Pengembangan',
            'link' => $request->type === 'Online' ? $request->link : null,
            'alamat_pelatihan' => $request->type === 'Offline' ? $request->alamat_pelatihan : null,
            'deskripsi' => $request->deskripsi,
            'created_by' => Auth::user()->id,
            'company_id' => Auth::user()->company_id,
            'kategori' => $request->kategori,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pelatihan berhasil dibuat.',
            'data' => $pelatihan,
        ], 200);
    }


    public function edit(Request $request, $id)
    {
        // Ambil data pelatihan berdasarkan ID
        $pelatihan = PelatihanSayaModel::findOrFail($id);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'judul_pelatihan' => 'required',
            'foto' => $pelatihan->foto ? 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120' : 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Jika ada foto sebelumnya, tidak wajib, jika tidak ada wajib diisi
            'vidio' => 'nullable|mimes:mp4,mov,avi|max:1048576', // Maks 1GB untuk video
            'type' => 'required|in:online,offline', // Wajib dan harus bernilai 'online' atau 'offline'
            'link' => 'nullable|required_if:type,online|url', // Link wajib diisi jika type = online
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
            'type' => 'Jenis Pelatihan',
            'link' => 'Link',
            'deskripsi' => 'Deskripsi Pelatihan',
        ]);

        // Menyimpan file gambar dan video baru jika ada, atau tetap menggunakan yang lama
        if ($request->hasFile('foto')) {
            // Hapus gambar lama jika ada
            if ($pelatihan->foto) {
                Storage::disk('public')->delete($pelatihan->foto);
            }
            // Simpan gambar baru ke dalam storage di folder 'public/pelatihan/foto'
            $pelatihan->foto = $request->file('foto')->store('pelatihan/foto', 'public');
        }

        if ($request->hasFile('vidio')) {
            // Hapus video lama jika ada
            if ($pelatihan->vidio) {
                Storage::disk('public')->delete($pelatihan->vidio);
            }
            // Simpan video baru ke dalam storage di folder 'public/pelatihan/vidio'
            $pelatihan->vidio = $request->file('vidio')->store('pelatihan/vidio', 'public');
        }

        // Update data pelatihan
        $pelatihan->update([
            'kategori' => $request->kategori,
            'judul_pelatihan' => $request->judul_pelatihan,
            'type' => $request->type,
            'link' => $request->type === 'online' ? $request->link : null, // Link hanya diisi jika type = online
            'alamat_pelatihan' => $request->type === 'Offline' ? $request->alamat_pelatihan : null,
            'deskripsi' => $request->deskripsi,
        ]);

        // Redirect atau respon sukses
        return response()->json([
            'status' => true,
            'message' => 'Pelatihan berhasil dibuat.',
            'data' => $pelatihan,
        ], 200);
    }


    // Fungsi untuk menghapus user (Delete)
    public function delete(Request $request)
    {
        // return $request->all();
        // Validasi input
        // $request->validate([
        //     'ids' => 'required|array',
        //     'ids.*' => 'exists:users,id', // Ganti dengan nama tabel Anda
        // ]);

        // Hapus data berdasarkan ID
        PelatihanSayaModel::whereIn('id', $request->ids)->delete();

        return response()->json(['status' => true, 'message' => 'Data berhasil dihapus.']);
    }
}
