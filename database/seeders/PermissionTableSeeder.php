<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\DB;
use App\Models\Administrator;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'parent_id' => 0,
                'title' => 'Administrators',
                'name' => 'manage-administrators',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 1,
                'title' => 'Administrator Listing',
                'name' => 'listing-administrator',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 1,
                'title' => 'Add New Administrator',
                'name' => 'add-administrator',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 1,
                'title' => 'Administrator Details',
                'name' => 'view-administrator',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 1,
                'title' => 'Edit Administrator',
                'name' => 'edit-administrator',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 1,
                'title' => 'Delete Administrator',
                'name' => 'delete-administrator',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 0,
                'title' => 'Users',
                'name' => 'manage-users',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 7,
                'title' => 'Users Listing',
                'name' => 'user-listing',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 7,
                'title' => 'Add New User',
                'name' => 'add-user',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 7,
                'title' => 'User Details',
                'name' => 'view-user',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 7,
                'title' => 'Edit User',
                'name' => 'edit-user',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 7,
                'title' => 'Delete User',
                'name' => 'delete-user',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 0,
                'title' => 'Roles',
                'name' => 'manage-role-permission',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 13,
                'title' => 'Roles Listing',
                'name' => 'role-listing',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 13,
                'title' => 'Add New Role',
                'name' => 'role-add',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 13,
                'title' => 'Role Details',
                'name' => 'role-view',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 13,
                'title' => 'Edit Role',
                'name' => 'role-edit',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 13,
                'title' => 'Delete Role',
                'name' => 'role-delete',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 0,
                'title' => 'Clients',
                'name' => 'manage-clients',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 19,
                'title' => 'Add New Client',
                'name' => 'add-client',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 19,
                'title' => 'Clients Listing',
                'name' => 'client-listing',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 19,
                'title' => 'Client Details',
                'name' => 'view-client',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 19,
                'title' => 'Edit Client',
                'name' => 'edit-client',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 19,
                'title' => 'Delete Client',
                'name' => 'delete-client',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 0,
                'title' => 'Plans',
                'name' => 'manage-plans',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 25,
                'title' => 'Add New Plan',
                'name' => 'add-plan',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 25,
                'title' => 'Plans Listing',
                'name' => 'plan-listing',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 25,
                'title' => 'Plan Details',
                'name' => 'view-plan',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 25,
                'title' => 'Edit Plan',
                'name' => 'edit-plan',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 25,
                'title' => 'Delete Plan',
                'name' => 'delete-plan',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 0,
                'title' => 'Email Templates',
                'name' => 'manage-email-templates',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 31,
                'title' => 'Add New Email Template',
                'name' => 'add-emailtemplate',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 31,
                'title' => 'Listing',
                'name' => 'emailtemplate-listing',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 31,
                'title' => 'Email Templates Details',
                'name' => 'view-emailtemplate',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 31,
                'title' => 'Edit Email Template',
                'name' => 'edit-emailtemplate',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 31,
                'title' => 'Delete Email Template',
                'name' => 'delete-emailtemplate',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 37,
                'title' => 'Edit Permission',
                'name' => 'edit-permission',
                'guard_name' => 'adminapi',
            ],
            [
                'parent_id' => 38,
                'title' => 'Role Permission',
                'name' => 'role-permission',
                'guard_name' => 'adminapi',
            ],
        ];
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
