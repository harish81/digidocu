<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create a global permissions
        Permission::create(['name'=>'create users']);
        Permission::create(['name'=>'read users']);
        Permission::create(['name'=>'update users']);
        Permission::create(['name'=>'delete users']);
        Permission::create(['name'=>'user manage permission']);

        Permission::create(['name'=>'create tags']);
        Permission::create(['name'=>'read tags']);
        Permission::create(['name'=>'update tags']);
        Permission::create(['name'=>'delete tags']);

        Permission::create(['name'=>'create documents']);
        Permission::create(['name'=>'read documents']);
        Permission::create(['name'=>'update documents']);
        Permission::create(['name'=>'delete documents']);
        Permission::create(['name'=>'verify documents']);
        //Permission::create(['name'=>'create comments']);
        //Permission::create(['name'=>'read comments']);
    }
}
