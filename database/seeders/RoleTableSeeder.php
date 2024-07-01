<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert(['level'    =>  'Super Admin']);
        DB::table('roles')->insert(['level'    =>  'Administrator']);
        DB::table('roles')->insert(['level'    =>  'Manager']);
        DB::table('roles')->insert(['level'    =>  'Pelapor']);
    }
}
