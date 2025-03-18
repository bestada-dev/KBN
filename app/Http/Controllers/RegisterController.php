<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\OTPMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Users;
use App\CompanyModel;
use App\ProvinceModel;
use App\CityModel;
use DB;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    function index(Request $request) {
        return view('pages.register.verify_email');
    }

    function formOtp(Request $request, $id) {
        return view('pages.register.form_verify_otp', [
            'get_email' => Users::where('id', $id)->with('role')->first()
        ]);
    }

    function cekEmail(Request $request) {
        $email = $request->input('email');

        $user = DB::table('users')->where('email', $email)->first();

        if($user){
            if($user->user_status_id == 2){

                $otp = rand(100000, 999999);

                Session::put('otp', $otp);

                Mail::to($email)->send(new OTPMail($otp));

                Users::where('id', $user->id)->update([
                    'otp' => $otp
                ]);


                if ($user) {
                    return response()->json([
                        'success' => true,
                        'role_id' => $user->role_id,
                        'user_id' => $user->id,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email tidak ditemukan.'
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda sudah ter register.'
                ]);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan.'
            ]);
        }

    }

    public function sendOTP(Request $request)
    {
        $otp = rand(100000, 999999);

        Session::put('otp', $otp);

        Mail::to($request->email)->send(new OTPMail($otp));

        return response()->json(['message' => 'OTP sent successfully']);
    }

    public function verifyOTP(Request $request)
    {
        // return $request->all();
        $cekUserOtp = Users::where('id', $request->userId)->first();

        // Verify the OTP
        if ($cekUserOtp->otp == $request->otp) {
            // Users::where('id', $cekUserOtp->id)->update([
            //     'otp' => null
            // ]);
            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully',
                'role_id' => $cekUserOtp->role_id,
                'user_id' => $cekUserOtp->id
            ]);
        } else {
            return response()->json(['message' => 'OTP yang anda masukan Salah'], 401);
        }
    }

    public function getCitiesByProvince($id)
    {
        // Ambil data kota berdasarkan ID provinsi
        $cities = CityModel::where('provinsi_id', $id)->get();

        // Kembalikan dalam format JSON
        return response()->json($cities);
    }

    // ===========================reguster untuk vendor===========================

    function vendor($id) {
        return view('pages.register.register_vendor', [
            'get_vendor' => Users::where('id', $id)->with('role')->first(),
            'get_prov' => ProvinceModel::get()
        ]);
    }

    public function registerVendor(Request $request)
    {
        // return $request->all();
        // Server-side validation with custom attribute names and messages
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            'confirmPassword' => 'required|same:password',
            'company_name' => 'required',
            'address' => 'required',
            'company_logo' => 'nullable|mimes:jpeg,png|max:5120', // Optional logo upload
        ], [
            // Custom messages for validation rules
            'required' => ':attribute tidak boleh kosong.',
            'same' => 'Konfirmasi Password harus sama dengan Password.',
            'min' => [
                'string' => 'Password minimal :min karakter.',
            ],
            'mimes' => 'File harus berupa gambar dengan format: :values.',
            'max' => [
                'file' => 'Ukuran file tidak boleh lebih dari :max kilobyte.',
            ],
        ], [
            // Custom attribute names for better readability in errors
            'password' => 'Password',
            'confirmPassword' => 'Konfirmasi Password',
            'company_name' => 'Nama Perusahaan',
            'privonce_id' => 'Provinsi',
            'city_id' => 'Kota',
            'address' => 'Alamat',
            'company_logo' => 'Logo Perusahaan',
        ]);

        // Handle validation failure
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // If validation passes, proceed with updating the vendor's data
        $createCompany = CompanyModel::create([
            'name' => $request->company_name,
            'address' => $request->address,
            'logo' => '-',
        ]);

        $users = Users::find($request->user_id); // Find users by user_id

        $users->company_id = $createCompany->id;
        $users->admin_name = $request->company_name;
        $users->company_name = $request->company_name;
        $users->privonce_id = $request->privonce_id;
        $users->city_id = $request->city_id;
        $users->address = $request->address;
        $users->user_status_id = 1;
        $users->is_new = 0;
        $users->otp = null;

        // If file is uploaded, handle file upload
        if ($request->hasFile('company_logo')) {
            $file = $request->file('company_logo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/logos'), $filename);
            $users->company_logo = $filename; // Assuming 'logo' is a field in your users table
        }

        // Update password if provided
        if ($request->password) {
            $users->password = bcrypt($request->password);
        }

        // return $users;

        // Save users data
        $users->save();

        return response()->json([
            'status' => 'success',
            'message' => 'users data updated successfully',
            'role_id' => $users->role_id, // Assuming role_id exists in your users table
        ]);
    }


    // ===========================Register Perusahaan ===========================
    function perusahaan($id) {
        return view('pages.register.register_perusahaan', [
            'get_perusahaan' => Users::where('id', $id)->with('role')->first(),
            'get_prov' => ProvinceModel::get()
        ]);
    }

    public function registerPerusahaan(Request $request)
    {
        // return $request->all();
        // Server-side validation with custom attribute names and messages
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            'confirmPassword' => 'required|same:password',
            'company_name' => 'required',
            'address' => 'required',
        ], [
            // Custom messages for validation rules
            'required' => ':attribute tidak boleh kosong.',
            'same' => 'Konfirmasi Password harus sama dengan Password.',
            'min' => [
                'string' => 'Password minimal :min karakter.',
            ],
            'mimes' => 'File harus berupa gambar dengan format: :values.',
            'max' => [
                'file' => 'Ukuran file tidak boleh lebih dari :max kilobyte.',
            ],
        ], [
            // Custom attribute names for better readability in errors
            'password' => 'Password',
            'confirmPassword' => 'Konfirmasi Password',
            'company_name' => 'Nama Perusahaan',
            'privonce_id' => 'Provinsi',
            'city_id' => 'Kota',
            'address' => 'Alamat',
        ]);

        // Handle validation failure
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // If validation passes, proceed with updating the vendor's data
        $createCompany = CompanyModel::create([
            'name' => $request->company_name,
            'address' => $request->address,
            'logo' => '-',
        ]);
        $users = Users::find($request->user_id); // Find users by user_id

        if (!$users) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $users->company_id = $createCompany->id;
        $users->admin_name = $request->company_name;
        $users->company_name = $request->company_name;
        $users->privonce_id = $request->privonce_id;
        $users->city_id = $request->city_id;
        $users->address = $request->address;
        $users->user_status_id = 1;
        $users->is_new = 0;
        $users->otp = null;

        // Update password if provided
        if ($request->password) {
            $users->password = bcrypt($request->password);
        }

        try {
            $users->save();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update user data'], 500);
        }



        return response()->json([
            'status' => 'success',
            'message' => 'users data updated successfully',
            'role_id' => $users->role_id, // Assuming role_id exists in your users table
        ]);
    }


    // ===========================Register Admin ===========================
    function admin(Request $request, $id) {
        // return $request->all();
        return view('pages.register.register_admin', [
            'get_admin' => Users::where('id', $id)->with('role')->first()
        ]);
    }

    public function registerAdmin(Request $request)
    {
        // return $request->all();
        // Server-side validation with custom attribute names and messages
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            'confirmPassword' => 'required|same:password',
            'admin_name' => 'required',
        ], [
            // Custom messages for validation rules
            'required' => ':attribute tidak boleh kosong.',
            'same' => 'Konfirmasi Password harus sama dengan Password.',
            'min' => [
                'string' => 'Password minimal :min karakter.',
            ],
            'mimes' => 'File harus berupa gambar dengan format: :values.',
            'max' => [
                'file' => 'Ukuran file tidak boleh lebih dari :max kilobyte.',
            ],
        ], [
            // Custom attribute names for better readability in errors
            'password' => 'Password',
            'confirmPassword' => 'Konfirmasi Password',
            'admin_name' => 'Nama Admin',
        ]);

        // Handle validation failure
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // If validation passes, proceed with updating the vendor's data
        $users = Users::find($request->user_id); // Find users by user_id

        $users->admin_name = $request->admin_name;
        $users->user_status_id = 1;
        $users->is_new = 0;
        $users->otp = null;

        // Update password if provided
        if ($request->password) {
            $users->password = bcrypt($request->password);
        }

        // Save users data
        $users->save();

        return response()->json([
            'status' => 'success',
            'message' => 'users data updated successfully',
            'role_id' => $users->role_id, // Assuming role_id exists in your users table
        ]);
    }

    // ===========================Register Employee ===========================
    function employe(Request $request, $id) {
        // return $request->all();
        return view('pages.register.register_employe', [
            'get_employe' => Users::where('id', $id)->with('role')->first()
        ]);
    }

    public function registerEmploye(Request $request)
    {
        // return $request->all();
        // Server-side validation with custom attribute names and messages
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            'confirmPassword' => 'required|same:password',
            'employe_name' => 'required',
            'nik' => 'required',
        ], [
            // Custom messages for validation rules
            'required' => ':attribute tidak boleh kosong.',
            'same' => 'Konfirmasi Password harus sama dengan Password.',
            'min' => [
                'string' => 'Password minimal :min karakter.',
            ],
            'mimes' => 'File harus berupa gambar dengan format: :values.',
            'max' => [
                'file' => 'Ukuran file tidak boleh lebih dari :max kilobyte.',
            ],
        ], [
            // Custom attribute names for better readability in errors
            'password' => 'Password',
            'confirmPassword' => 'Konfirmasi Password',
            'employe_name' => 'Nama Karyawan',
            'nik' => 'NIK',
        ]);

        // Handle validation failure
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // If validation passes, proceed with updating the vendor's data
        $users = Users::find($request->user_id); // Find users by user_id

        $users->employe_name = $request->employe_name;
        $users->nik = $request->nik;
        $users->user_status_id = 1;
        $users->is_new = 0;
        $users->otp = null;

        // Update password if provided
        if ($request->password) {
            $users->password = bcrypt($request->password);
        }

        // Save users data
        $users->save();

        return response()->json([
            'status' => 'success',
            'message' => 'users data updated successfully',
            'role_id' => $users->role_id, // Assuming role_id exists in your users table
        ]);
    }

}
