<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(array(
            'name' => 'super-admin',
            'lastname' => 'super',
            'phone' => '3194065226',
            'email' => 'super@mail.com',
            'password' => Hash::make('0000'),
            'theme' => 'super',
            'photo' => null,
        ));      
    }
}
