<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminPermissions = [
            'manage-managers', // CRUD on Managers
            'manage-receptionists', // CRUD on Receptionists
            'manage-clients', // CRUD on Clients
            'sudo', // Full access
            'update-profile', // Update own profile
        ];

        // Define permissions for Manager
        $managerPermissions = [
            'manage-receptionists', // CRUD on Receptionists
            'ban-receptionist', // Ban/Unban Receptionists
            'manage-floors', // CRUD on Floors
            'manage-rooms', // CRUD on Rooms
            'manage-clients', // CRUD on Clients
            'update-profile', // Update own profile
        ];

        // Define permissions for Receptionist
        $receptionistPermissions = [
            'view-pending-clients', // View unapproved clients
            'approve-clients', // Approve clients
            'view-approved-clients', // View approved clients
            'view-client-reservations', // View client reservations
            'update-profile', // Update own profile
        ];

        // Define permissions for Client
        $clientPermissions = [
            'view-own-reservations', // View own reservations
            'make-reservation', // Make a reservation
            'update-profile', // Update own profile
        ];

        // Combine all permissions and create them
        $allPermissions = array_unique(array_merge(
            $adminPermissions,
            $managerPermissions,
            $receptionistPermissions,
            $clientPermissions
        ));

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
