<?php

namespace Database\Seeders;

use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[
              {
                "name": "admin",
                "guard_name": "web",
                "permissions": [
                  "view_customer",
                  "view_any_customer",
                  "create_customer",
                  "update_customer",
                  "restore_customer",
                  "restore_any_customer",
                  "replicate_customer",
                  "reorder_customer",
                  "delete_customer",
                  "delete_any_customer",
                  "force_delete_customer",
                  "force_delete_any_customer",
                  "view_menu",
                  "view_any_menu",
                  "create_menu",
                  "update_menu",
                  "restore_menu",
                  "restore_any_menu",
                  "replicate_menu",
                  "reorder_menu",
                  "delete_menu",
                  "delete_any_menu",
                  "force_delete_menu",
                  "force_delete_any_menu",
                  "view_notification",
                  "view_any_notification",
                  "create_notification",
                  "update_notification",
                  "restore_notification",
                  "restore_any_notification",
                  "replicate_notification",
                  "reorder_notification",
                  "delete_notification",
                  "delete_any_notification",
                  "force_delete_notification",
                  "force_delete_any_notification",
                  "view_role",
                  "view_any_role",
                  "create_role",
                  "update_role",
                  "delete_role",
                  "delete_any_role",
                  "view_user",
                  "view_any_user",
                  "create_user",
                  "update_user",
                  "restore_user",
                  "restore_any_user",
                  "replicate_user",
                  "reorder_user",
                  "delete_user",
                  "delete_any_user",
                  "force_delete_user",
                  "force_delete_any_user",
                  "page_SiteSettings",
                  "page_Themes",
                  "page_MyProfile",
                  "page_Logs",
                  "page_HealthCheckResults",
                  "page_TwoFactor",
                  "widget_StatsOverviewWidget",
                  "widget_TotalSales"
                ]
              },
              {"name": "user", "guard_name": "web", "permissions": []}
        ]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (!blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = Utils::getRoleModel()::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name']
                ]);

                if (!blank($rolePlusPermission['permissions'])) {

                    $permissionModels = collect();

                    collect($rolePlusPermission['permissions'])
                        ->each(function ($permission) use ($permissionModels) {
                            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                                'name' => $permission,
                                'guard_name' => 'web'
                            ]));
                        });
                    $role->syncPermissions($permissionModels);

                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (!blank($permissions = json_decode($directPermissions, true))) {

            foreach ($permissions as $permission) {

                if (Utils::getPermissionModel()::whereName($permission)->doesntExist()) {
                    Utils::getPermissionModel()::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
