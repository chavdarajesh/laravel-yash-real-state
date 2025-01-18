<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        SiteSetting::truncate();
        $Faqs = [
            [
                'key' => 'header_logo',
                'value' => null,
                'title' => 'Header Logo',
                'description' => null,
                'status' => 1,
                'order' => 1,
                'created_at' => Carbon::now('Asia/Kolkata'),
            ],
            [
                'key' => 'footer_logo',
                'value' => null,
                'title' => 'Footer Logo',
                'description' => null,
                'status' => 1,
                'order' => 2,
                'created_at' => Carbon::now('Asia/Kolkata'),
            ], [
                'key' => 'favicon',
                'value' => null,
                'title' => 'Favicon',
                'description' => null,
                'status' => 1,
                'order' => 3,
                'created_at' => Carbon::now('Asia/Kolkata'),
            ],
            [
                'key' => 'loader',
                'value' => null,
                'title' => 'Loader',
                'description' => null,
                'status' => 1,
                'order' => 4,
                'created_at' => Carbon::now('Asia/Kolkata'),
            ],

        ];
        SiteSetting::insert($Faqs);
    }
}
