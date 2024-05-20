<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT; //panggil library jwt
use Firebase\JWT\Key; //panggil library jwt key

class Usermiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // ambiltokendari bearer
        $jwt = $request->bearerToken();

        // kondisi token null atau string kosong
        if (is_null($jwt) || $jwt == '') {
            return response()->json(['messages' => 'Anda tidak memiliki otoritas, token tidak terpenuhi'], 401);
        } else {
            // kondisi token tidak null dan bukan string kosong

            // decypt token
            $decoded = JWT::decode($jwt, new Key(env('JWT_SECRET_KEY'), 'HS256'));

            // kondisi jika token memiliki hak akses admin
            if ($decoded->role == 'admin') {

                // lanjut ke controller atau step selanjutnya
                return $next($request);

            }
            // kondisi ketika bukan admin
            return response()->json(['messages' => 'Anda tidak memiliki otoritas, token tidak terpenuhi'], 401);
        }
    }
}
