<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Users;

class LoginCheck
{
    public function handle(Request $request, Closure $next)
    {
        // Session::put('data', [
        //     'token' => 'asda12222222222asdas12312313',
        //     'user'  => ['username' => 'Muhamad Sobari asd', 'role_name' => 'Admin', 'role_id' => 1]
        // ]);

        $skip = [
            'login', 'logout', 'reset-password-after-login'
        ];

        // dd($request->path());

        if(in_array($request->path(), $skip)){
            return $next($request);
        }

        if($request->path() === '/' || strpos($request->path(), 'login/') !== false || strpos($request->path(), 'reset-password-after-logi') !== false){

            // URL default untuk login
            if(Session::has('data')){

                return redirect(url('admin'))->with([
                    'type'    => 'success',
                    'title'    => 'Success',
                    'message' => "Anda berhasil login",
                    'timeOutSuccess' => 2000
                ]);

            }else{

                return $next($request);

            }

        }else{

            // URL selain login
            if(Session::has('data')){
                if(___set($request->path())){

                    return $next($request);

                }else{

                    return abort(404);

                }

            }else{

                return redirect(url(''))->with([
                    'type'    => 'error',
                    'title'    => 'Ops..',
                    'message' => 'Anda harus masuk terlebih dahulu!',
                ]);

            }

        }
    }
}
