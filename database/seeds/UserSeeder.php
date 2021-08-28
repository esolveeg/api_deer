<?php

use App\Phone;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = ['name' => 'test' , 'email' => 'test@elnozom.com' , 'password' => bcrypt(123456)];
        $admin = ['name' => 'admin' , 'email' => 'admin@elnozom.com' , 'type' => 1 , 'password' => bcrypt(123456)];
        $user = User::create($user);
        $admin = User::create($admin);
        $phoneRecord = [ 
            [
                'Phone' =>'01022052546',
                'userId' =>$user->id,
            ],
            [
                'Phone' =>'0102205255',
                'userId' =>$admin->id,
            ]
        ];
        
        $phone = Phone::insert($phoneRecord);
    }
}
