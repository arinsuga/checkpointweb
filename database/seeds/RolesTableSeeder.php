<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //Reset table
        DB::table('roles')->delete();

        DB::table("roles")->insert([
            "id" => 1,
            "app_id" => 1,
            "code" => "att-sa",
            "name" => "Super Admin Attendance Tracking",
            "description" => "Full Kontrol Attendance Tracking"
        ]); 
        DB::table("roles")->insert([
            "id" => 2,
            "app_id" => 1,
            "code" => "att-adm",
            "name" => "Admin Attendance Tracking",
            "description" => "Admin Attendance Tracking"
        ]); 
        DB::table("roles")->insert([
            "id" => 3,
            "app_id" => 1,
            "code" => "att-usr",
            "name" => "User Attendance Tracking",
            "description" => "User Attendance Tracking"
        ]); 
                                        
    }
}
