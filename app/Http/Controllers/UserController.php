<?php

namespace App\Http\Controllers;


use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; //Library validator
use Illuminate\Support\Facades\Auth; //library Auth
use Firebase\JWT\JWT; //Memanggil library jwt
use Illuminate\Support\Facades\Hash; // Library Hash



class UserController extends Controller
{
    public function login(Request $request)
    {
        // melakukan validasi
        $validator = validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // kondisi ketika inputan salah
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);

        }
        // kondisi inputan ada ditabel user
        if (Auth::attempt($validator->validated())) {
            $payload = [

                'name'  => Auth::user()->name,
                'email' => Auth::user()->email,
                'role'  => Auth::user()->role,
                'iat'   => Carbon::now()->timestamp, //Waktu ketika token digenerete
                'exp'   => Carbon::now()->timestamp + 7200 //waktu ketika token sudah expired
            ];

            // generate token
            $jwt = JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');
            // kirim token keuser
            return response()->json([
                'messages' => 'Token Berhasil di Generete',
                'name'     => Auth::user()->name,
                'token'    => 'Bearer ' . $jwt
            ], 200);
        }
        // kondisi ketika user yang diinputkan tidak ada ditabel user
        return response()->json(['Message' => 'Email  atau Password Salah'], 422);

    }

    public function register(Request $request)
    {
        $validator = validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        $validated = $validator->validated();

        if (User::where('email', $validated['email'])->exists()) {
            return response()->jsn(['msg' => 'Email Telah Dipakai'], 400);
        }
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(["data" => ['msg' => 'Registrasi Berhasil', 'data' => $user]], 200);
    }
}
