<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //TODO: implement AdminSeeder logic after designing database structure
        // email: admin@admin.com
        // password: 123456
        $admin = User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            // 'id' => 1,
            'role' => 'admin',
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456'),
            'is_approved' => true,
            'email_verified_at' => Carbon::now(),
           'mobile' => '+1234567890',
           'national_id' => '00000000000000',
           'avatar_img' => '/public/avatar.png',
           'country' => 'XXX',
           'gender' => 'male',
        ]);     

    }
}
