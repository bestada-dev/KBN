<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

use DB;

use Validator;
use Exception;
use Session;
use stdClass;

use App\Users;
use App\AccessToken;
use App\RequestResetPassword;
use App\Jobs\SendEmailJob;

use Carbon\Carbon;

class AuthController extends Controller
{

    public function page()
    {
        if(Auth::check()) {
            return redirect(url('/'));
        }

    	return view('pages.login');
    }

    public function login(Request $request)
    {
        $rules = [
            'email'    => 'required|email|max:100|regex:/^\S*$/u',
            'password' => 'required'
        ];

        try{

            $access = null;

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json([
                    'message' => ucfirst($validator->errors()->first()),
                    'status'  => false
                ], 400);
            }

            $user = Users::where('email', $request->email)->first();

            if(!$user){
                return response()->json([
                    'message' => "Email yang Anda masukkan belum terdaftar.",
                    'status'  => false
                ], 404);
            }

            if(Hash::check($request->password, $user->password)){

                switch($user->user_status_id){

                    // ACTIVE
                    case 1:
                        if(!$user->token){
                            $user->token = __generateToken();
                        }

                        $user->save();

                        $token = __generateToken();

                        $access = new AccessToken;
                        $access->token = $token;
                        $access->save();

                        return response()->json([
                            'message'      => 'Login berhasil.',
                            'status'       => true,
                            'data'         => $user,
                            'token'        => $user->token,
                            'access_token' => $token
                        ], 200);
                        break;

                    // PENDING
                    case 2:
                        return response()->json([
                            'message' => 'Email akun Anda belum di verifikasi.',
                            'status'  => false,
                            'data'    => [
                                'status' => $user->user_status_id
                            ]
                        ], 401);
                        break;

                    // SUSPENDED
                    case 3:
                        $user->token = null;
                        $user->save();

                        return response()->json([
                            'message' => 'Akun Anda telah dinonaktifkan sementara/selamanya.',
                            'status'  => false,
                            'data'    => [
                                'status' => $user->user_status_id
                            ]
                        ], 401);
                        break;

                }

            }else{

                return response()->json([
                    'message' => 'Kata sandi yang Anda masukkan salah.',
                    'status'  => false
                ], 401);

            }

        }catch (Exception $e){

            return ____error([__FUNCTION__, 'Line : '.$e->getLine(). ' Message : '.$e->getMessage()]);

        }
    }

    public function checkLogin($id)
    {
        try{

            $data = explode('_', $id);

            $access = $data[0];
            $token  = $data[1];

            $checkAccess = AccessToken::where('token', $access)->first();

            if(!$checkAccess){

                return view('errors.401');

                // OLD
                // return response()->json([
                //     'message' => 'Anda tidak memiliki akses.',
                //     'status'  => false
                // ], 401);

            }

            $user = Users::where('token', $token)->first();

            AccessToken::where('token', $access)->delete();

            if ($user->is_new) {
                return redirect(url('/reset-password-after-login/'.$user->token));
            } else {
                Session::put('data', [
                    'token' => $user->token,
                    'user'  => $user
                ]);

                if (!Auth::check()) {
                    // dd($user);
                    Auth::login($user);
                }

                // dd(Auth::user());
                // Pengecekan role_id dan redirect berdasarkan role
                switch ($user->role_id) {
                    case 1:
                        return redirect(url('/superadmin/dashboard'));
                    case 2:
                        return redirect(url('/vendor/pelatihan_saya'));
                    case 3:
                        return redirect(url('/employe/training'));
                    case 4:
                        return redirect(url('/company/employe'));
                    default:
                        // Redirect default jika role_id tidak sesuai dengan yang diharapkan
                        return redirect(url('/dashboard')); // ganti sesuai kebutuhan
                }
            }


        }catch(Exception $e){

            return ____error([__FUNCTION__, 'Line : '.$e->getLine(). ' Message : '.$e->getMessage()]);

        }
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return redirect(url(''));
    }

    public function register(Request $request)
    {
        $rules = [
            'username'     => 'required|min:4|max:100',
            'email'        => 'required|email|max:100|regex:/^\S*$/u|unique:users',
            'password'     => 'required|min:8',
            'phone'        => 'nullable|min:12|max:15|regex:/^\S*$/u|unique:users',
            'job_position' => 'required|string|max:100',
        ];

        try{

            $this->__runValidation($request->all(), $rules);

            DB::beginTransaction();

            $user = Users::create([
                'username'     => $request->username,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
                'phone'        => str_replace('-', '', $request->phone),
                'job_position' => $request->job_position,
                'notes'        => $request->notes,
                'user_status_id' => 1,
                'is_new'       => true,
                'is_admin'     => $request->is_admin,
                'role_id'      => $request->is_admin ? 2 : 3, // 1: Admin, 2 : Admin , 3 : User
            ]);

            $user->projects()->sync($request->projects);

            DB::commit();

            return response()->json([
                'message' => 'Pendaftaran berhasil!',
                'status'  => true,
                'data'    => [
                    'email'      => $user->email,
                    // 'url_verify' => url('api/email/verify') . '?email=' . $user->email
                ]
            ], 200);

        }catch(Exception $e){

            DB::rollback();

            return ____error([__FUNCTION__, 'Line : '.$e->getLine(). ' Message : '.$e->getMessage()]);

        }
    }

    public function verification($id)
    {
        try{

            $id   = Crypt::decryptString($id);
            $user = Users::where('id', $id)->first();

            if(!$user){
                return redirect(url(''));
            }

            if($user->user_status_id !== 2){
                return redirect(url(''));
            }

            $user->user_status_id = 1;
            $user->save();

            return redirect(url('/'))->with([
                'message' => "<b style='font-size:17px;'>Selamat!</b><br>Email Anda telah berhasil diverifikasi! Silahkan masuk."
            ]);

        }catch(Exception $e){

            return ____error([__FUNCTION__, 'Line : '.$e->getLine(). ' Message : '.$e->getMessage()]);

        }
    }

    // Handles password reset request
    public function requestPasswordReset(Request $request)
    {
        $rules = [
            'email' => 'required|email|max:100|regex:/^\S*$/u'
        ];

        try{

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json([
                    'message' => ucfirst($validator->errors()->first()),
                    'status'  => false
                ], 400);
            }

            $user = Users::where('email', $request->email)->first();

            if(!$user){
                return response()->json([
                    'message' => "Email yang Anda masukkan belum terdaftar.",
                    'status'  => false
                ], 404);
            }

            if($user->user_status_id === 3){
                return response()->json([
                    'message' => "Akun Anda telah dinonaktifkan sementara/selamanya.",
                    'status'  => false
                ], 404);
            }

            $CHECK_REQUEST = RequestResetPassword::where('email', '=', $request->email)
            ->whereDate('created_at', Carbon::today())
            ->count();

            if($CHECK_REQUEST >= 3){
                return response()->json([
                    'message' => "Hari ini Anda telah melakukan permintaan perubahan kata sandi sebanyak " .$CHECK_REQUEST. " kali, coba lagi besok.",
                    'status'  => false
                ], 400);
            }

            DB::beginTransaction();

            $__token = __generateToken();

            RequestResetPassword::create([
                'email' => $request->email,
                'token' => $__token
            ]);

            /* Kirim Email */
            $data_email = [
                'email' => $request->email,
                'name'  => $user->username,
                'type'  => 'reset',
                'link'  => url('reset-password/' . $__token)
            ];



            dispatch(new SendEmailJob($data_email));
            __logEmail('SUCCESS', $request->email, __FUNCTION__, 'OK', json_encode($data_email));

            DB::commit();

            return response()->json([
                'message' => 'OK.',
                'status'  => true,
                // 'data'    => [
                //     'redirect' => url('reset-password/' . Crypt::encryptString($user->email))
                // ]
            ], 200);

        }catch (Exception $e){

            return ____error([__FUNCTION__, 'Line : '.$e->getLine(). ' Message : '.$e->getMessage()]);

        }
    }

    // Handles the password reset process
    public function handleResetPassword($param)
    {
        try{
            Crypt::decryptString($param);

            return redirect(url('/'))->with([
                'message' => "<b style='font-size:17px;'>Periksa Email Anda</b><br>Periksa email Anda untuk mengatur ulang kata sandi akun Anda."
            ]);

        }catch(DecryptException $e){
            if($param === _['SUCCESS_CODE']){
                return redirect(url('/'))->with([
                    'type'    => 'success',
                    'title'    => 'Password Created!',
                    'message' => "New password has been successfully created. Please login with your new password.",
                    'timeOutSuccess' => 5000
                ]);
            }

            $REQ = RequestResetPassword::where('token', $param)->first();

            if(!$REQ){
                return view('errors.404');
            }

            $exp = $this->expiredTokenResetPassword($REQ);

            if(Session::has('data')){
                Session::flush();
            }

            return view('pages.create-new-password', [
                'token'   => $param,
                'expired' => $exp,
                'type'    => TYPE_UPDATE_PASSWORD['RESET_PASSWORD'],
            ]);

        }
    }

    // Updates the user's password
    public function updatePassword(Request $request, $type = null)
    {

        // DEFAULT
        if ($type === null) {
            $type = TYPE_UPDATE_PASSWORD['CREATE_NEW_PASSWORD_AFTER_LOGIN'];
        }

        $rules = [
            'password'   => 'required|min:8|max:30',
            'password_2' => 'required|min:8|max:30'
        ];

        try{

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json([
                    'message' => ucfirst($validator->errors()->first()),
                    'status'  => false
                ], 400);
            }


            if($request->password !== $request->password_2){
                return response()->json([
                    'message' => 'Kata sandi baru yang Anda masukkan tidak cocok.',
                    'status'  => false
                ], 400);
            }

            // Create New Password after Login
            if($type === TYPE_UPDATE_PASSWORD['CREATE_NEW_PASSWORD_AFTER_LOGIN']) {

                $user = Users::where('token', $request->token)->first();

                if(!$user){
                    return response()->json([
                        'message' => 'Email belum terdaftar',
                        'status'  => false
                    ], 400);
                }

                DB::beginTransaction();

                $user->password = Hash::make($request->password);
                $user->is_new   = false;
                $user->save();

                DB::commit();

                return response()->json([
                    'message' => 'Kata sandi berhasil diubah.',
                    'status'  => true,
                    'data'    => [
                        'redirect' => url('reset-password/'._['SUCCESS_CODE'])
                    ]
                ], 200);
            }

            // Forgot Password
            if($type ===  TYPE_UPDATE_PASSWORD['RESET_PASSWORD']) {

                $REQ = RequestResetPassword::where('token', $request->token)->first();

                if(!$REQ){
                    return response()->json([
                        'message' => 'Invalid token.',
                        'status'  => false
                    ], 400);
                }

                $exp = $this->expiredTokenResetPassword($REQ);

                if($exp === true){
                    return response()->json([
                        'message' => 'Maaf, token telah expired! Harap reload halaman terlebih dahulu.',
                        'status'  => false
                    ], 400);
                }

                $user = Users::where('email', $REQ->email)->first();

                if(!$user){
                    return response()->json([
                        'message' => 'Email belum terdaftar',
                        'status'  => false
                    ], 400);
                }

                DB::beginTransaction();

                $user->password = Hash::make($request->password);
                $user->save();

                $REQ->created_at = '1999-01-01 00:00:00';
                $REQ->save();

                DB::commit();

                return response()->json([
                    'message' => 'Kata sandi berhasil diubah.',
                    'status'  => true,
                    'data'    => [
                        'redirect' => url('reset-password/'._['SUCCESS_CODE'])
                    ]
                ], 200);
            }



        }catch (Exception $e){

            return ____error([__FUNCTION__, 'Line : '.$e->getLine(). ' Message : '.$e->getMessage()]);

        }
    }

    private function expiredTokenResetPassword($data)
    {
        $now = Carbon::now();
        $expired = Carbon::parse($data->created_at)->addHours(2);

        if(strtotime($expired) > strtotime($now)){
            return false;
        }else{
            return true;
        }
    }

    private function convertDate($date)
    {
        /*  Contoh
            $date = '22 April 2021'; */

        $date = explode(' ', strtolower($date));

        $months = [
            'januari'   => '01', 'februari' => '02', 'maret'    => '03', 'april'    => '04',
            'mei'       => '05', 'juni'     => '06', 'juli'     => '07', 'agustus'  => '08',
            'september' => '09', 'oktober'  => '10', 'november' => '11', 'desember' => '12'
        ];

        $date = $date[2] . '-' . $months[$date[1]] . '-' . $date[0];

        return $date;
    }
}
