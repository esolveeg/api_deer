<?php

use App\Group;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
       DB::update("UPDATE products SET featured = 1 , latest = 1  WHERE id < 5");
       DB::update("UPDATE groups SET featured = 1 WHERE id < 5");

        
    }
}
