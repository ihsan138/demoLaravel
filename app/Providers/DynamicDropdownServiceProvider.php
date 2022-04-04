<?php

namespace App\Providers;
use App\Models\Admin\Role; //write model name here which we created
use App\Models\Admin\User; //write model name here which we created
use App\Models\Admin\Lists; //write model name here which we created

use Illuminate\Support\ServiceProvider;
use Auth;

class DynamicDropdownServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*',function($view)
        {
            $view
            ->with('dynamicRoles', Role::all()
                ->where('name','!=','Super Admin')
                ->where('name','!=','Admin')
            ); // use eloquent such as ->where('category','user') to retrieve array which use to store all table data,
            
            $view
            ->with('dynamicUsers', User::all()
                ->where('name','!=','Super Admin')
                ->where('name','!=','Admin')
            ); // use eloquent such as ->where('category','user') to retrieve array which use to store all table data,
            
        });
    }
}
