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
        $this->call(PassportSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(CouponSeeder::class);
        $this->call(BannerSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(AuthorSeeder::class);
    }
}
