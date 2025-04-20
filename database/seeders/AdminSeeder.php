<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

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
        echo "creating admin";
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456'),
            'is_approved' => true,
            'email_verified_at' => now(),
            'created_by' => 1,
            'approved_by' => 1
        ]);        
    }
}
