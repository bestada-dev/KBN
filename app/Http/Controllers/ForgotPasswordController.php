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
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    function index(Request $request) {
        return view('pages.forgot-password.verify_email');
    }

    function formForgotPassword(Request $request) {
        return view('pages.forgot-password.forgot-password');
    }

    function formOtp(Request $request, $id) {
        return view('pages.forgot-password.form_verify_otp', [
            'get_email' => Users::where('id', $id)->with('role')->first()
        ]);
    }

    function cekEmail(Request $request) {
        $email = $request->input('email');

        $user = DB::table('users')->where('email', $email)->first();

        if($user){

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
                'message' => 'Email tidak ditemukan.'
            ]);
        }

    }

    public function changePassword(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'min:8'],
            'confirmPassword' => ['required', 'same:password'],
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cari user berdasarkan ID
        $user = Users::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password berhasil diubah.'
        ]);
    }
}
