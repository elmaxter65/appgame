<?php

use App\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $status = Status::all();
        if($status->isEmpty()){
            DB::table('statuses')->insert([
                'name' => 'Bronze',
                'points' => 499,
                
            ]);
            DB::table('statuses')->insert([
                'name' => 'Silver',
                'points' => 999,
            ]);
            DB::table('statuses')->insert([
                'name' => 'Gold',
                'points' => 2999,
            ]);
            DB::table('statuses')->insert([
                'name' => 'Platinum',
                'points' => 4999,
            ]);
            DB::table('statuses')->insert([
                'name' => 'Diamond',
                'points' => 1000000,
            ]);
        }
        
    }
}
