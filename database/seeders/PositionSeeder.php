<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('positions')->insert([
            'name'          => "Admin",
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        DB::table('positions')->insert([
            'name'          => "Company Admin",
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        DB::table('positions')->insert([
            'name'          => "Agent",
            'created_at'    => date('Y-m-d H:i:s')
        ]);
    }
}
