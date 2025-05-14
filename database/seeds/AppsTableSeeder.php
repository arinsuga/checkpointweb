<?php

use Illuminate\Database\Seeder;

class AppsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Reset table
        DB::table('apps')->delete();
        DB::table("apps")->insert([
            "id" => 1,
            "code" => "att",
            "name" => "Attendance Tracking System",
            "description" => "Attandance Tracking System",
        ]); 
        
    }
}
