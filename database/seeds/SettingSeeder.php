<?php

use App\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'key' => 'logo',
                'value' => 'settings/logo.png',
                'type' => 'image',
            ],
            [
                'key' => 'address',
                'value' => '3 ابراهيم سليمان , متفرع من شارع شهاب , المهندسين , الجيزة',
                'type' => 'text',
            ],
            [
                'key' => 'phone',
                'value' => '0123456789',
                'type' => 'text',
            ],
            [
                'key' => 'email',
                'value' => 'info@elnozom.com',
                'type' => 'email',
            ],
            [
                'key' => 'about',
                'value' =>"شركة و كيان مميز أنشئت عام 1994 ولها دور رائد في السوق المصري بحلول وبرامج متميزة سهلة الاستخدام فى جميع قطاعات التجزئة و الجملة.",
                'type' => 'textarea',
            ],
            [
                'key' => 'facebook',
                'value' => 'https://www.facebook.com/ElNozomSystems/',
                'type' => 'text',
            ],
            [
                'key' => 'instagram',
                'value' => 'https://www.instagram.com/elnozomeg/',
                'type' => 'text',
                
            ],

        ];
        Setting::insert($settings);
    }
}
