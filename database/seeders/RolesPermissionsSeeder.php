<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\BackedEnumValueResolver;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        // $adminRole = Role::create(['name' => 'admin', 'type' => 'Admin']);
        // $warehouseManagerRole = Role::create(['name' => 'warehouse manager', 'type' => 'Warehouse']);
        // $assistantManagerRole = Role::create(['name' => 'assistant manager', 'type' => 'Warehouse']);
        // $distributionAgentRole = Role::create(['name' => 'distribution agent', 'type' => 'DistributionCenter']);

        $adminRole = Role::findByName('admin');
        $warehouseManagerRole = Role::findByName('warehouse manager');
        $distributionAgentRole = Role::findByName('distribution agent');


        // Define permissions
        $adminPermissions = [
            'category.index', 'category.store', 'category.show', 'category.update', 'category.destroy',
            'warehouse.index', 'warehouse.store', 'warehouse.centers.index', 'warehouse.show', 'warehouse.update', 'warehouse.own.show',
            'center.index', 'center.store', 'center.show', 'center.update', 'center.own.show',
            'product.index', 'product.store', 'product.show', 'product.update', 'product.min.update', 'product.stored.update',
            'employee.index', 'employee.store', 'employee.show', 'employee.update',
            'manufacturer.index', 'manufacturer.store', 'manufacturer.show', 'manufacturer.update',
            'orders.index', 'orders.buy.store', 'orders.manufacturer.store', 'orders.show', 'orders.sell.update', 'orders.buy.update', 'orders.own.update',
            'shippingCompany.index', 'shippingCompany.store', 'shippingCompany.show', 'shippingCompany.update',
            'shipment.index', 'shipment.store', 'shipment.show',
            'role.index', 'role.store',
            'sale.index', 'sale.store', 'sale.show',
            'destruction.index', 'destruction.store', 'destruction.show',
            'manager.continue',
            'employee.ban',
            'product.expired.notify', 'product.warning.notify'
        ];

        // Create permissions
        foreach ($adminPermissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        // Define permissions
        $warehousePermissions = [
            'category.index', 'category.show',
            'warehouse.index', 'warehouse.centers.index', 'warehouses.product.index',
            'center.own.show',
            'product.index', 'product.show', 'product.min.update', 'product.stored.update',
            'manufacturer.index', 'manufacturer.show',
            'orders.index', 'orders.buy.store', 'orders.manufacturer.store', 'orders.show', 'orders.sell.update', 'orders.buy.update', 'orders.own.update',
            'shippingCompany.index', 'shippingCompany.show',
            'shipment.index', 'shipment.store', 'shipment.show',
            'destruction.index', 'destruction.store', 'destruction.show',
            'product.expired.notify', 'product.warning.notify'
        ];

        // Create permissions
        foreach ($warehousePermissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        // Define permissions
        $centerPermissions = [
            'category.index', 'category.show',
            'warehouse.own.show', 'warehouse.product.index',
            'product.index', 'product.show', 'product.min.update', 'product.stored.update',
            'orders.index', 'orders.buy.store', 'orders.show', 'orders.buy.update', 'orders.own.update',
            'sale.index', 'sale.store', 'sale.show',
            'destruction.index', 'destruction.store', 'destruction.show',
            'product.expired.notify', 'product.warning.notify'
        ];

        // Create permissions
        foreach ($centerPermissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        // Assign permissions to roles
        $adminRole->syncPermissions($adminPermissions); // delete old permissions and keep those inside the $permissions
        $warehouseManagerRole->syncPermissions($warehousePermissions);
        // $assistantManagerRole->syncPermissions($permissions);
        $distributionAgentRole->syncPermissions($centerPermissions);

        //////////////////////////////////////////////////////////////

        // Create users and assign roles
        // $adminUser = User::factory()->create([
        //     'name' => 'admin',
        //     'email' => 'admin@example.com',
        //     'password' => bcrypt('password'),
        // ]);
        // $adminUser->assignRole($adminRole);

        // // Assign permission associated with the role to the user
        // $permissions = $adminRole->permissions()->pluck('name')->toArray();
        // $adminUser->givePermissionTo($permissions);

        //////////////////////////////////////////////////////////////

        //        // Create users and assign roles
        //        $clientUser = User::factory()->create([
        //            'name' => 'Client User',
        //            'email' => 'client@example.com',
        //            'password' => bcrypt('password'),
        //        ]);
        //        $clientUser->assignRole($clientRole);
        //
        //        // Assign permission associated with the role to the user
        //        $permissions = $clientRole->permissions()->pluck('name')->toArray();
        //        $clientUser->givePermissionTo($permissions);
    }
}
