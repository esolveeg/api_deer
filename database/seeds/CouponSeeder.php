<?php

use App\Coupon;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coupons = [
            [
                 'code' => 'TWINTY',
                 'value' => 20,
                 'type' => 'fixed',
                 'expiresAt' => Carbon::parse('30-03-2021'),
            ],
            [
                 'code' => 'EXPIRED',
                 'value' => 20,
                 'type' => 'fixed',
                 'expiresAt' => Carbon::parse('30-02-2021'),
             ],
             [
                 'code' => 'TEN',
                 'value' => 10,
                 'type' => 'percent',
                 'expiresAt' => Carbon::parse('30-03-2021'),
             ],
        ];
 
        Coupon::insert($coupons);
    }
}
