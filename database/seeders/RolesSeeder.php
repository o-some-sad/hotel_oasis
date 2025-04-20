<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $receptionistRole = Role::firstOrCreate(['name' => 'receptionist']);
        $clientRole = Role::firstOrCreate(['name' => 'client']);

        // Assign permissions to roles
        $adminRole->syncPermissions([
            'manage-managers',
            'manage-receptionists',
            'manage-clients',
            'sudo',
            'update-profile',
        ]);

        $managerRole->syncPermissions([
            'manage-receptionists',
            'ban-receptionist',
            'manage-floors',
            'manage-rooms',
            'manage-clients',
            'update-profile',
        ]);

        $receptionistRole->syncPermissions([
            'view-pending-clients',
            'approve-clients',
            'view-approved-clients',
            'view-client-reservations',
            'update-profile',
        ]);

        $clientRole->syncPermissions([
            'view-own-reservations',
            'make-reservation',
            'update-profile',
        ]);
    }
}
