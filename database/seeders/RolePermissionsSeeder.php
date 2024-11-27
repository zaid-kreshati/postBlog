<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Media;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Define Permissions
        $adminPermissions = ['category.create', 'category.delete', 'category.update', 'category.index',
                            'activities.index', 'metrix.index'];

        $userPermissions = ['post.create', 'post.delete', 'post.update', 'post.index',
                            'comment.create', 'comment.update', 'comment.delete', 'search'];

        // Create Permissions
        $allPermissions = array_unique(array_merge($adminPermissions, $userPermissions));

        foreach ($allPermissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web' ]);
        }

        // Assign Permissions to Roles
        $adminRole->syncPermissions($adminPermissions); // Admin gets all admin permissions
        $userRole->syncPermissions($userPermissions);   // User gets all user permissions



        // Create Users and Assign Roles
        // Admin user
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole($adminRole);




        // Regular user
        $user1 = User::factory()->create([
            'name' => 'Zaid',
            'email' => 'zaid@user.com',
            'password' => bcrypt('password'),


        ]);
        $user1->assignRole($userRole);

        // Regular user
        $user2 = User::factory()->create([
            'name' => 'rama',
            'email' => 'rama@user.com',
            'password' => bcrypt('password'),


        ]);
        $user2->assignRole($userRole);


        // Regular user
        $user3 = User::factory()->create([
            'name' => 'ali',
            'email' => 'ali@user.com',
            'password' => bcrypt('password'),


        ]);
        $user3->assignRole($userRole);



        // Regular user
        $user4 = User::factory()->create([
            'name' => 'yousef',
            'email' => 'yousef@user.com',
            'password' => bcrypt('password'),


        ]);
        $user4->assignRole($userRole);

        // Regular user
        $user5 = User::factory()->create([
            'name' => 'omar',
            'email' => 'omar@user.com',
            'password' => bcrypt('password'),


        ]);
        $user5->assignRole($userRole);

        // Regular user
        $user6 = User::factory()->create([
            'name' => 'mohammed',
            'email' => 'mohammed@user.com',
            'password' => bcrypt('password'),


        ]);
        $user6->assignRole($userRole);


        // Regular user
        $user7 = User::factory()->create([
            'name' => 'ahmad',
            'email' => 'ahmad@user.com',
            'password' => bcrypt('password'),


        ]);
        $user7->assignRole($userRole);

        // Regular user
        $user8 = User::factory()->create([
            'name' => 'noor',
            'email' => 'noor@user.com',
            'password' => bcrypt('password'),


        ]);
        $user8->assignRole($userRole);





    }
}
