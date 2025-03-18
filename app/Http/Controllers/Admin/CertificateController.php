<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use App\CompanyModel;
use Auth;

use App\Certificate;

class CertificateController extends Controller
{
    function index(){
        // dd( DB::table('users')->where('role_id', 4)->get());
        return view('pages.admin.certificate.index', [
            'get_perusahaan' => DB::table('users')->where('role_id', 4)->get()
        ]);
    }

    public function generateQrCode($id)
    {
        // Retrieve the certificate by ID
        $certificate = Certificate::findOrFail($id);

        // Format the URL for the QR code
        $url = url("preview-certificate/{$certificate->certificate_number}");
        // dd();

        // Generate the QR code with the logo in the center
        $qrCode = QrCode::format('png')
            ->size(150) // Set the QR code size
            ->merge('sidebar.png', .3, true) // Merge logo at 30% size
            ->generate($url);

        // Return the QR code as a PNG image
        return response($qrCode)->header('Content-Type', 'image/png');
    }

    public function previewCertificate($certificateNumber)
{
    // Fetch and display the certificate details using the certificate number
    return view('pages.admin.certificate.preview', [
        'data' => Certificate::with(['pelatihan.getVendor', 'user.company'])->where('certificate_number', $certificateNumber)->firstOrFail()
    ]);
}


    function detail($id){
        // return AksesPelatihanSettingModel::with('getPelatihanDanPengembangan')->where('id', $id)->first();
        // home_photos/672dc08415037.1731051652.png
        // dd(Certificate::with(['pelatihan.getVendor', 'user.company'])->find($id));
        return view('pages.employe.certificate.detail', [
            'data' => Certificate::with(['pelatihan.getVendor', 'user.company'])->find($id)
        ]);
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

            // Total data count for pagination
            $totalData = Certificate::with(['user.company', 'pelatihan'])
                            ->count();
            $totalFiltered = $totalData;
            $posts = '';

            // Handle search functionality
            $search = $request->input('search.value');
            $kategori_pelatihan = $request->input('pelatihan_id'); // Get the type filter value
            $judul_pelatihan_id = $request->input('judul_pelatihan_id'); 

            $query = Certificate::with(['user.company', 'pelatihan']);

            if (!empty($search)) {
                $query->where(function ($q) use ($search, $kategori_pelatihan, $judul_pelatihan_id) {
                    $q
                    // ->where('id', 'LIKE', "%{$search}%")
                    //   ->orWhere('certificate_number', 'LIKE', "%{$search}%")
                      ->whereHas('user.company', function ($q) use ($search) {
                            $q->where('admin_name', 'LIKE', "%{$search}%");
                        });
                    //   ->orWhereHas('pelatihan', function ($q) use ($search) {
                    //       $q->where('judul_pelatihan', 'LIKE', "%{$search}%");
                    //   });

                 
                });
            }

               // Include the type filter if it's set
            if (!empty($kategori_pelatihan)) {
                $query->where(function ($q) use ($search, $kategori_pelatihan) {
                    $q->orWhereHas('pelatihan', function ($q) use ($kategori_pelatihan) {
                        $q->where('kategori', 'LIKE', "%{$kategori_pelatihan}%");
                    });
                });
            }

            if (!empty($judul_pelatihan_id)) {
                $query->where(function ($q) use ($search, $judul_pelatihan_id) {
                    $q->orWhereHas('pelatihan', function ($q) use ($judul_pelatihan_id) {
                        $q->where('id', $judul_pelatihan_id);
                    });
                });
            }

            // Get filtered results with pagination
            $posts = $query->offset($start)
                           ->limit($limit)
                           ->orderBy($order, $dir)
                           ->get();

            $totalFiltered = $query->count(); // Update the total filtered count

            // Populate data array
            foreach ($posts as $a) {
                $data[] = $a;
            }

            // Prepare JSON response
            $json_data = [
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data
            ];

            return response()->json($json_data);

        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function storeOrUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'pelatihan_id' => 'required|exists:pelatihan_saya,id',
            'director' => 'nullable|string|max:255',
            'another' => 'nullable|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Check if the certificate already exists for the given user_id and pelatihan_id
        $existingCertificate = Certificate::where('user_id', $request->user_id)
            ->where('pelatihan_id', $request->pelatihan_id)
            ->first();
    
        if ($existingCertificate) {
            // If the certificate exists, update it
            $existingCertificate->update([
                'director' => $request->director,
                'another' => $request->another,
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Certificate updated successfully.',
                'data' => $existingCertificate
            ], 200);
        }
    
        // If the certificate does not exist, create a new one
        $certificateNumber = generateCertificateNumber(); // Assuming this is a function that returns a certificate number
    
        $certificate = Certificate::create([
            'certificate_number' => $certificateNumber,
            'user_id' => $request->user_id,
            'pelatihan_id' => $request->pelatihan_id,
            'director' => $request->director,
            'another' => $request->another,
        ]);
    
        return response()->json([
            'status' => true,
            'message' => 'Certificate created successfully.',
            'data' => $certificate
        ], 201);
    }
    

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'pelatihan_id' => 'required|exists:pelatihan_saya,id',
        'director' => 'nullable|string|max:255',
        'another' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Check if the certificate already exists for the given user_id and pelatihan_id
    $existingCertificate = Certificate::where('user_id', $request->user_id)
        ->where('pelatihan_id', $request->pelatihan_id)
        ->first();

    if ($existingCertificate) {
        return response()->json([
            'status' => false,
            'message' => 'Data sudah ada'
        ], 409); // 409 Conflict status code
    }

    // Generate the certificate number
    $certificateNumber = generateCertificateNumber(); // Assuming this is a function that returns a certificate number

    // Create the certificate record
    $certificate = Certificate::create([
        'certificate_number' => $certificateNumber,
        'user_id' => $request->user_id,
        'pelatihan_id' => $request->pelatihan_id,
        'director' => $request->director,
        'another' => $request->another,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Certificate created successfully.',
        'data' => $certificate
    ], 201);
}


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'pelatihan_id' => 'required|exists:pelatihan_saya,id',
            'director' => 'nullable|string|max:255',
            'another' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $certificate = Certificate::findOrFail($id);

        // Check if certificate_number is empty and generate a new one if needed
        if (empty($certificate->certificate_number)) {
            $certificate->certificate_number = generateCertificateNumber();
        }

        $certificate->update([
            'user_id' => $request->user_id,
            'pelatihan_id' => $request->pelatihan_id,
            'director' => $request->director,
            'another' => $request->another,
        ]);

        return response()->json(['success' => 'Certificate updated successfully.', 'data' => $certificate], 200);
    }


    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);

        $certificate->delete();

        return response()->json(['success' => 'Certificate deleted successfully.'], 200);
    }

    public function delete(Request $request)
    {
        // Hapus data berdasarkan ID
        Certificate::whereIn('id', $request->ids)->delete();

        return response()->json(['status' => true, 'message' => 'Data berhasil dihapus.']);
    }

}
