<?php

use App\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sliders = [
            ['image' => 'sliders/01.webp' , 'type' => 0],
            ['image' => 'sliders/banners/01.jpg', 'type' => 1],
            // ['image' => 'sliders/03.jpg'],
        ];
       
        Banner::insert($sliders);
        
    }
}
