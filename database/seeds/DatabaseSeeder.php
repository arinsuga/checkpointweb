<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

		//User Table
        $this->call('UsersTableSeeder');
        // factory(App\User::class, 5)->create();

        //App Table
        $this->call('AppsTableSeeder');
        $this->call('AppUserTableSeeder');
        
        //Role Table
        $this->call('RolesTableSeeder');
        $this->call('RoleUserTableSeeder');

    }
}
