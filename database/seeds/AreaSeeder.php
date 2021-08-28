<?php

use App\Area;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $areas = [
            [
                "areaName" => "Mohandseen",
                "deliveryServiceTotal" => 100,
                "postalCode" => "!2234",
            ],
            [
                "areaName" => "New Cairo",
                "deliveryServiceTotal" => 100,
                "postalCode" => "!2234",
            ],
            [
                "areaName" => "Nasr City",
                "deliveryServiceTotal" => 100,
                "postalCode" => "!2234",
            ],
            [
                "areaName" => "Shehab",
                "deliveryServiceTotal" => 100,
                "postalCode" => "!2234",
                "sectionId" => 1
            ],
            [
                "areaName" => "Marghany",
                "deliveryServiceTotal" => 100,
                "postalCode" => "!2234",
                "sectionId" => 2
            ],
            [
                "areaName" => "Abas El aqad",
                "deliveryServiceTotal" => 100,
                "postalCode" => "!2234",
                "sectionId" => 3
            ]
        ];

        foreach($areas as $area)
        {
            $area = Area::create($area);
        }

    }
}
