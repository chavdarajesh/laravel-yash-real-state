<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Admin@123'),
            'phone' => 9876543210,
            'username' => 'admin',
            'address' => 'test test address',
            'dateofbirth' => '2022-06-15',
            'status' => 1,
            'is_verified' => 1,
            'created_at'=>Carbon::now('Asia/Kolkata'),
            'otp'=> null,
            'email_verified_at'=>Carbon::now('Asia/Kolkata'),
            'referral_code'=>'admin',
        ]);
    }
}
