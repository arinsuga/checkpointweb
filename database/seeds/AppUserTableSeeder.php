<?php

use Illuminate\Database\Seeder;

class AppUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Reset table
        DB::table('app_user')->delete();
        DB::table("app_user")->insert([
            "id" => 1,
            "app_id" => 1,
            "user_id" => 1,
            "created_at" => null,
            "updated_at" => null
        ]); 
        DB::table("app_user")->insert([
            "id" => 2,
            "app_id" => 1,
            "user_id" => 2,
            "created_at" => null,
            "updated_at" => null
        ]); 
        DB::table("app_user")->insert([
            "id" => 3,
            "app_id" => 1,
            "user_id" => 3,
            "created_at" => null,
            "updated_at" => null
        ]); 
        DB::table("app_user")->insert([
            "id" => 4,
            "app_id" => 1,
            "user_id" => 4,
            "created_at" => null,
            "updated_at" => null
        ]); 
                
    }
}
