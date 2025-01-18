<?php

namespace Database\Seeders;

use App\Models\ContactSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContactSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ContactSetting::truncate();
        
        DB::table('contact_settings')->insert([
            'static_id' => 1,
            'email' => 'contact@website.com',
            'phone' => '+919876543210',
            'location' => '6391 Elgin St. Delaware 10299',
            'map_iframe' => '<iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4562.753041141002!2d-118.80123790098536!3d34.152323469614075!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80e82469c2162619%3A0xba03efb7998eef6d!2sCostco+Wholesale!5e0!3m2!1sbn!2sbd!4v1562518641290!5m2!1sbn!2sbd"
            class="google-map__one" allowfullscreen></iframe>',
            'timing' => 'Visit Anytime',
            'status' => 1,
            'created_at'=>Carbon::now('Asia/Kolkata')
        ]);
    }
}
