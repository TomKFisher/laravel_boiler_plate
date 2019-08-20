<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->addRolesAndPermissions();
    }

    private function addRolesAndPermissions()
    {
        // create permissions for an admin
        $adminPermissions = collect([
            'browse-role', 'read-role', 'edit-role', 'add-role', 'delete-role', 
            'browse-user', 'read-user', 'edit-user', 'add-user', 'delete-user',
            'browse-audit', 'read-audit', 'restore-audit'
        ])->map(function ($name) {
            return Permission::create(['name' => $name]);
        });
        // add admin role
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrators',
            'description' => 'Main administrator accounts. Admins can perform all tasks, inc. Role and User management.'
        ]);
        $adminRole->givePermissionTo($adminPermissions);

        // add a default user role
        Role::create([
            'name' => 'user',
            'display_name' => 'Users',
            'description' => 'Users can perform most tasks. The majority of your users will have the User role.'
        ]);
    }
}