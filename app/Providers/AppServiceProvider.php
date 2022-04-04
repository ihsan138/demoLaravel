<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use App\Models\Admin\User;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        //Add this custom validation rule.
        Validator::extend('alpha_spaces', function ($attribute, $value) {
            // This will only accept alpha and spaces.
            // If you want to accept hyphens use: /^[\pL\s-]+$/u.
            return preg_match('/^[\pL\s]+$/u', $value);

        });
        Validator::extend('alpha_num_spaces', function ($attribute, $value) {
            // This will only accept alpha and spaces.
            // If you want to accept hyphens use: /^[\pL\s-]+$/u.
            return preg_match('/^[a-zA-Z0-9 ]*$/u', $value);
        });

        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {

            if (Auth::check())
            {
                $userid = Auth::id();
            }else{
                $userid = 0;
            }

            // For fa icons
            // https://fontawesome.com/v5.15/icons?d=gallery&p=2&q=money
            // $event->menu->add('MAIN NAVIGATION');
            $event->menu->add(
                [
                    'header' => 'DASHBOARD',
                ],
                [
                    'text'    => 'Dashboard',
                    'icon'    => 'fas fa-fw fa-home',
                    'url'  => '/home',
                    // 'can'  => ['manage users'],
                ],
                [
                    'header' => 'USER AREA',
                ],
                [
                    'text'    => 'Users',
                    'icon'    => 'fas fa-fw fa-users',
                    'url'  => 'users',
                    // 'can'  => ['manage users'],
                ],
                [
                    'text'    => 'My profile',
                    'icon'    => 'fas fa-fw fa-user',
                    'url'  => "profiles/".$userid,
                    'can'  => ['Manage profile'],
                ],
                [
                    'text'    => 'Calendar',
                    'icon'    => 'fas fa-fw fa-calendar',
                    'url'  => '/calendar/event',
                    'can'  => ['Manage calendar'],
                ],
                [
                    'text' => 'File Directory',
                    'url'  => "/filemanagers",
                    'icon' => 'fas fa-fw fa-folder-open',
                    'can'  => ['Manage files'],
                ],
                [
                    'text'    => 'Inventory',
                    'url'  => '/inventory/dashboard',
                    'icon'    => 'fas fa-fw fa-boxes',
                    'can'  => ['Manage inventory'],
                ],
                [
                    'text'    => 'Purchase',
                    'url'  => '/purchase/dashboard',
                    'icon'    => 'fas fa-fw fa-briefcase',
                    'can'  => ['Manage purchase'],
                ],
                [
                    'text'    => 'Sale',
                    'url'  => '/sale/dashboard',
                    'icon'    => 'fas fa-fw fa-file-invoice-dollar',
                    'can'  => ['Manage sales'],
                ],
                //FOR ADMIN
                [
                    'header' => 'ADMIN AREA',
                    'can'  => ['Manage settings'],
                ],
                [
                    'text'    => 'Company',
                    'url'  => '#',
                    'icon'    => 'fas fa-fw fa-building',
                    'can'  => ['Manage company'],
                    'submenu' => [
                        [
                            'text' => 'Main',
                            'url'  => '/company/main',
                            'icon' => 'fas fa-fw fa-building',
                            'shift'   => 'ml-2',
                        ],
                        [
                            'text' => 'Company Type',
                            'url'  => '/company/type',
                            'icon' => 'fas fa-fw fa-warehouse',
                            'shift'   => 'ml-2',
                        ],
                        [
                            'text' => 'Company Location',
                            'url'  => '/company/location',
                            'icon' => 'fas fa-fw fa-map',
                            'shift'   => 'ml-2',
                        ],
                        [
                            'text' => 'Company Department',
                            'url'  => '/company/department',
                            'icon' => 'fas fa-fw fa-user-circle',
                            'shift'   => 'ml-2',
                        ],
                    ],
                ],
                [
                    'text'    => 'Settings',
                    'url'  => '#',
                    'icon'    => 'fas fa-fw fa-cog',
                    'can'  => ['Manage settings'],
                    'submenu' => [
                        [
                            'text' => 'Roles',
                            'url'  => "roles",
                            'icon' => 'fas fa-fw fa-user-cog',
                            'can'  => ['Manage roles'],
                        ],
                        [
                            'text' => 'Permissions',
                            'url'  => "permissions",
                            'icon' => 'fas fa-fw fa-user-shield',
                            'can'  => ['Manage permissions'],
                        ],
                    ],
                ],
                [
                    'header' => 'TEMPLATE AREA',
                    'can'  => ['manage template'],
                ],
                [
                    'text'    => 'Template',
                    'icon'    => 'fas fa-fw fa-user',
                    'can'  => ['Manage template'],
                    'submenu' => [
                        [
                            'text' => 'Basic',
                            'url'  => "/template/basic",
                            'icon' => 'fas fa-fw fa-clipboard',
                        ],
                    ],
                ]
            );
        });
    }
}
