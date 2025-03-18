<?php

namespace App\Http\Middleware;

use Closure;
use Exception;

use Illuminate\Support\Facades\Session;

class SideBarMiddleware
{
    public function handle($request, Closure $next)
    {
        try{

            $ROLE = Session::get('data');

            if($ROLE) {

                $ROLE = $ROLE['user']->role_id;
                $URL  = $request->path();

                $SETTING = _settingSidebar();

                if(array_key_exists($URL, $SETTING)){

                    if(!in_array($ROLE, $SETTING[$URL])){
                        
                        // echo "Anda tidak memiliki hak akses ke halaman ini.";
                        return abort(401);


                    }

                }else{

                    // echo "[SB Middleware] Error: Url " . $URL . " belum di set.";
                    return abort(404);

                }

                return $next($request);

            }


            return redirect(url('/'));

                //throw $th;
        }catch (Exception $e) {
            return abort($e->getStatusCode());
        }
    }
}
