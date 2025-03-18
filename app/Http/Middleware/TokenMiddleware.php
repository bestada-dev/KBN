<?php

namespace App\Http\Middleware;

use Closure;
use Validator;
use Exception;

use DB;
use App\Users;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class TokenMiddleware
{
    public function handle($request, Closure $next)
    {
        $rules = [
            'token' => 'required|max:255|regex:/^\S*$/u',
        ];

        try{

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Invalid token.',
                    'status'  => false,
                    'error'   => ucfirst($validator->errors()->first())
                ], 401);
            }

            $token = Users::select('token')
                        ->where(['token' => $request->token])
                        ->first();



            if($request->has('token')) {
                // dd(Session::all());
                if (!Auth::check()) {
                    $user =Users::where('token', $request->token)->first();
                    Auth::login($user);
                }
            }


            if(!$token){
                return response()->json([
                    'message' => 'Invalid token.',
                    'status'  => false
                ], 401);
            }

            unset($request['token']);

            // Check for 'status' in the request and update its value if necessary
            if ($request->has('status')) {
                $request->merge(['status' => true]);
             } else {
                // $request->merge(['status' => false]);
            }


            if($request->has('userLoggedIn')) {
                if (!Auth::check()) {
                    $user =Users::find($request->userLoggedIn);
                    Auth::login($user);
                }
            }

            return $next($request);

        }catch(Exception $e){

            return response()->json([
                'message' => 'Terdapat kesalahan pada sistem internal.',
                'status'  => false,
                'error'   => '[Middleware] ' . $e->getMessage()
            ], 500);

        }
    }
}
