<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert("INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
        (1, NULL, 'Laravel Personal Access Client', 'pLJSqAMiUw6gB2XeD7qQcbFF5oOkFWTuxVmzBPO7', 'http://localhost', 1, 0, 0, '2021-01-14 09:13:01', '2021-01-14 09:13:01'),
        (2, NULL, 'Laravel Password Grant Client', 'xlN48fLBTxGrr8xIJAseDiv8WwX1MwBFabjVvujr', 'http://localhost', 0, 1, 0, '2021-01-14 09:13:01', '2021-01-14 09:13:01');");
    }
}
