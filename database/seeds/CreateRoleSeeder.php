<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name'=>'admin', 'display_name'=>'Quản trị hệ thống'],
            ['name'=>'student', 'display_name'=>'Học viên'],
            ['name'=>'developer', 'display_name'=>'Phát triển hệ thống'],
            ['name'=>'questionManager', 'display_name'=>'Quản lí ngân hàng câu hỏi'],
            
        ]);
    }
}
