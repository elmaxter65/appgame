<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => Crypt::encryptString('admin@admin.com'),
            'password' => Hash::make('Newpass1234$'),
            'blocked' => 0,
            'created_at' => Carbon::now(),
            'email_verified_at' => Carbon::now(),
        ]);
    }
}
