<?php

namespace App\Http\Middleware;

use Closure;
use Exception;

use Illuminate\Support\Facades\Session;

class LoginMiddleware
{
    public function handle($request, Closure $next)
    {
        try{

            if(!Session::has('data')){
                return redirect(url('login'));
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
