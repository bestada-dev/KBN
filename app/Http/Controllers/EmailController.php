<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

use Validator;
use Exception;

use App\Jobs\SendEmailJob;

use App\LogEmail;
use App\Users;

class EmailController extends Controller
{
	private function send($type, $data)
	{
		$data['type'] = $type;

		return dispatch(new SendEmailJob($data));
	}

	private function log($status, $to, $type, $message = null, $data = null)
	{
		$param = [
			'status' => $status,
			'to'     => $to,
			'type'	 => $type
		];

		$message !== null ? $param['message'] = $message : null;
		$data    !== null ? $param['data']    = $data    : null;

		LogEmail::create($param);
	}

    public function verify(Request $request)
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

            // $this->log('REQUEST', $request->email, __FUNCTION__);

            $user = Users::where('email', $request->email)->first();

            if(!$user){
            	return response()->json([
            		'message' => 'User tidak ditemukan.',
            		'status'  => false
            	], 404);
            }

            if($user->status_id !== 2){
            	return response()->json([
            		'message' => 'Status user sudah ' . DB::table('user_status')->find($user->status_id)->name . '.',
            		'status'  => false
            	], 401);
            }

            $data = [
            	'email' => $request->email,
            	'name'  => $user->fullname,
            	'link'  => url('user/verification') . '/' . Crypt::encryptString($user->id)
            ];

            $this->send(__FUNCTION__, $data);

            $this->log('SUCCESS', $request->email, __FUNCTION__, 'OK', json_encode($data));

            return response()->json([
            	'message' => 'OK',
            	'status'  => true
            ], 200);

        }catch(Exception $e){

        	return ____error([__FUNCTION__, 'Line : '.$e->getLine(). ' Message : '.$e->getMessage()]);

        }
    }
}
