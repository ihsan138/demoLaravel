<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; //cannot use Storage;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Carbon\Carbon;
use App\Models\Admin\User;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /***********************************************************************************************************/
        //Delete folders
        $directory = 'users/';                               //Set folder path and create if not exist
        Storage::disk('private')->deleteDirectory($directory);
        Storage::disk('public')->deleteDirectory($directory);

        /***********************************************************************************************************/
        //Create roles
        Role::create(['name' => 'SuperAdmin','description' => 'Access to everything','status' => 'active']);   //me  \.O./

        /***********************************************************************************************************/
        //Create permissions
        //This permission is restricted to SuperAdmin only.
        Permission::create(['name' => 'Manage template']);

        //These permissions are restricted to SuperAdmin and WebAdmin only.
        Permission::create(['name' => 'Manage permissions']);
        Permission::create(['name' => 'Manage roles']);

        //To manage system
        Permission::create(['name' => 'Manage settings']);
        Permission::create(['name' => 'Manage backup']);
        Permission::create(['name' => 'Manage users']);

        //Staff level, intern excluded
        Permission::create(['name' => 'Manage calendar']);
        Permission::create(['name' => 'Manage files']);
        
        //Everybody can manage their profile
        Permission::create(['name' => 'Manage profile']);

        /**********************  Admin level ********************* */
        $role = Role::findByName('SuperAdmin');
        $role->syncPermissions(['Manage profile', 'Manage files', 'Manage users', 'Manage settings', 'Manage backup', 'Manage roles', 'Manage permissions','Manage template','Manage calendar']);
        
        /***********************************************************************************************************/
        //Create a user 'Super Admin' #1
        $user = new User;
        $user->status = 'Admin';
        $user->name = 'Super Admin';
        $user->username = 'Super Admin';
        $user->email = 'superadmin@admin.com';
        $user->designation = 'SuperAdmin';
        $user->password = Hash::make('123123123');
        $user->email_verified_at = Carbon::now();
        $user->approved_at = Carbon::now();
        $user->save();
        $user->assignRole('SuperAdmin');
    }
}
