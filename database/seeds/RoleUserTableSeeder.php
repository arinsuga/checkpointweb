<?php

use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Reset table
        DB::table('role_user')->delete();

        DB::table("role_user")->insert([
            "id" => 1,
            "app_id" => 1,
            "role_id" => 1,
            "user_id" => 1,
            "created_at" => null,
            "updated_at" => null,
        ]); 

        DB::table("role_user")->insert([
            "id" => 2,
            "app_id" => 1,
            "role_id" => 2,
            "user_id" => 2,
            "created_at" => null,
            "updated_at" => null,
        ]); 

        DB::table("role_user")->insert([
            "id" => 3,
            "app_id" => 1,
            "role_id" => 3,
            "user_id" => 3,
            "created_at" => null,
            "updated_at" => null,
        ]); 

        DB::table("role_user")->insert([
            "id" => 4,
            "app_id" => 1,
            "role_id" => 3,
            "user_id" => 4,
            "created_at" => null,
            "updated_at" => null,
        ]); 

    }
}
