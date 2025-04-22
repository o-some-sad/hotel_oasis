<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, let's make sure we have an admin user to approve clients
        $adminId = User::firstWhere('role', 'admin')?->id;
        
        if (!$adminId) {
            $adminId = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'mobile' => '1234567890',
                'national_id' => '12345678901234',
                'country' => 'EGY',
                'gender' => 'male',
                'avatar_img' => null,
            ])->id;
        }
        
        // Create 10 sample clients
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Test Client {$i}",
                'email' => "client{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => 'client',
                'mobile' => "123456789{$i}",
                'national_id' => sprintf('%014d', $i),
                'country' => 'EGY',
                'gender' => $i % 2 === 0 ? 'male' : 'female',
                'is_approved' => true,
                'created_by' => $adminId,
                'approved_by' => $adminId,
                'avatar_img' => null,
            ]);
        }
    }
}
