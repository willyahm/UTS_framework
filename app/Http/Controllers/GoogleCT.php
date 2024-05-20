<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleCT extends Controller
{
    public function login()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {

            $google_user = Socialite::driver('google')->user();
            $user        = User::where('email', $google_user->email)->first();
            if ($user) {
                Auth::login($user);
                return redirect('user');
            } else {
                $name_user = User::create([
                    'name'           => ucwords($google_user->name),
                    'email'          => $google_user->email,
                    'password'       => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'remember_token' => Str::random(10),
                ]);
                Auth::login($user);
                return redirect('user');
            }
        } catch (\Throwable $th) {
            abort(404);
        }

    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
