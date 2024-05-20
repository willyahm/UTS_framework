<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; //Panggil model User
use Illuminate\Support\Facades\Hash; // Library Hash

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::faktory(10)->create(); //membuat 10 user secara otomatis
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@mail.com',
            'password' => Hash::make('admin'),
            'role'     => 'admin'
        ]);
    }
}
