<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'manage users',
            'manage roles',
            'manage obat',
            'import data',
            'perform stock-opname',
            'view reports',
            'export reports',
            'process mutasi',
            'manage settings',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Super admin: all permissions
        $super = Role::firstOrCreate(['name' => 'super-admin']);
        $super->givePermissionTo(Permission::all());

        // Admin
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo([
            'manage obat',
            'import data',
            'perform stock-opname',
            'view reports',
            'export reports',
            'process mutasi',
        ]);

        // Manager
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->givePermissionTo([
            'perform stock-opname',
            'view reports',
            'export reports',
            'process mutasi',
        ]);

        // Staff
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $staff->givePermissionTo([
            'manage obat',
            'perform stock-opname',
            'process mutasi',
        ]);

        // Viewer
        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        $viewer->givePermissionTo([
            'view reports',
        ]);

        // Assign existing first user to super-admin so initial user keeps full access
        $user = User::first();
        if ($user) {
            $user->assignRole('super-admin');
        }
    }
}
