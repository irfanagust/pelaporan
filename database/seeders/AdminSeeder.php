<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name'          =>  'Super',
            'last_name'     =>  'Admin',
            'level'         =>  1,
            'telp'          =>  '082182133457',
            'username'      =>  'tessuper',
            'password'      =>   bcrypt('123'),
        ]);
        DB::table('users')->insert([
            'name'          =>  'Faiz',
            'last_name'     =>  'Mandraguno',
            'level'         =>  2,
            'telp'          =>  '082182133457',
            'username'      =>  'tesadmin',
            'password'      =>   bcrypt('123'),
        ]);
        DB::table('users')->insert([
            'name'          =>  'Rusdi',
            'last_name'     =>  'Salim',
            'level'         =>  3,
            'telp'          =>  '082182133457',
            'username'      =>  'tesmanager',
            'password'      =>   bcrypt('123'),
        ]);
        DB::table('users')->insert([
            'name'          =>  'Wisnu',
            'last_name'     =>  'Ponorogo',
            'level'         =>  4,
            'telp'          =>  '082345235567',
            'username'      =>  'tespelapor',
            'password'      =>   bcrypt('123'),
        ]);
    }
}
