<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/********************************************************************************************************************************************/
/* These routes are for testing purposes                                                                                                    */
/********************************************************************************************************************************************/

/********************************************************************************************************************************************/
/* These routes are for general purposes                                                                                                    */
/********************************************************************************************************************************************/
Auth::routes(['verify' => true]);
Route::get('/', function () { return view('welcome'); });
Route::get('/inactivity', 'HomeController@inactivity')->middleware(['prevent-back-history']);

/********************************************************************************************************************************************/
/* These routes are for module purposes                                                                                                     */
/********************************************************************************************************************************************/
//All role can access these

Route::group(['middleware' => ['auth', 'verified', 'web']], function() {
    //Home
    Route::get('/home', 'HomeController@index')->name('home');

    //Change passwords by users
    Route::get('change-password', 'ChangePasswordController@index');
    Route::post('change-password', 'ChangePasswordController@store')->name('change.password');

    //Profile
    Route::resource('profiles', 'Admin\ProfileController',
        ['except' => ['create', 'store', 'destroy']
    ]);
    
    /************************************ */
    /* Calendars                          */
    /************************************ */
    /* CALIBRATION ************************/
    /********************************************************** calendar/calibration **********************************************************/
    Route::post('/calendar/calibration/updateDroppable/{id}', 'Calendar\Calibration\CalendarController@updateDroppable');
    Route::get('/calendar/calibration/eventRecurringData', 'Calendar\Calibration\CalendarController@eventRecurringData');

    Route::get('/calendar/calibration/getDatatable/{type}', 'Calendar\Calibration\CalendarController@getDatatable')->name('calendar.calibration.getDatatable');

    Route::resource('/calendar/calibration', 'Calendar\Calibration\CalendarController',
        ['except' => ['create', 'show', 'edit'],
        'as' => 'calendar'
    ]);

    /********************************************************** calendar/calibration/category **********************************************************/
    Route::get('/calendar/calibration/category/restore/{id}', 'Calendar\Calibration\CategoryController@restore')->name('calendar.calibration.category.restore');
    Route::get('/calendar/calibration/category/getDatatable/{type}', 'Calendar\Calibration\CategoryController@getDatatable')->name('calendar.calibration.category.getDatatable');

    Route::resource('/calendar/calibration/category', 'Calendar\Calibration\CategoryController', [
        'as' => 'calendar.calibration' //add prefix to named route
    ]);

    /* EVENT ******************************/
    /********************************************************** calendar/event **********************************************************/
    Route::post('/calendar/event/updateDroppable/{id}', 'Calendar\Event\CalendarController@updateDroppable');
    Route::get('/calendar/event/eventData', 'Calendar\Event\CalendarController@eventData');
    Route::resource('/calendar/event', 'Calendar\Event\CalendarController',
        ['except' => ['create', 'show', 'edit'],
        'as' => 'calendar'
    ]);

    /********************************************************** calendar/event/category **********************************************************/
    Route::get('/calendar/event/category/restore/{id}', 'Calendar\Event\CategoryController@restore')->name('calendar.event.category.restore');
    Route::get('/calendar/event/category/getDatatable/{type}', 'Calendar\Event\CategoryController@getDatatable')->name('calendar.event.category.getDatatable');

    Route::resource('/calendar/event/category', 'Calendar\Event\CategoryController', [
        'as' => 'calendar.event' //add prefix to named route
    ]);

    /************************************ */
    /* File Manager                       */
    /************************************ */
    Route::get('/filemanagers', 'Admin\FilemanagerController@index')->name('filemanagers.index');

    /************************************ */
    /* Users                              */
    /************************************ */
    Route::get('/users/restore/{id}', 'Admin\UserController@restore')->name('users.restore');
    Route::get('/users/getDatatable/{type}', 'Admin\UserController@getDatatable')->name('users.getDatatable');
    Route::resource('users', 'Admin\UserController');
    
});

/********************************************************************************************************************************************/
/* These routes are for role admin                                                                                                          */
/********************************************************************************************************************************************/
//Only role:admin can access these
Route::group(['middleware' => ['auth', 'verified', 'web', 'isAdmin',]], function() {
    
    /************************************ */
    /* Users                              */
    /************************************ */
    Route::put('/users/approve/{id}', 'Admin\UserController@approve')->name('users.approve');
    Route::put('/users/reject/{id}', 'Admin\UserController@reject')->name('users.reject');
    

    /************************************ */
    /* Change password by admin           */
    /************************************ */
    Route::get('/changePasswordAdmin/{user_id}', 'ChangePasswordController@admin')->name('change.password.admin');
    Route::post('changePasswordAdminStore', 'ChangePasswordController@adminStore')->name('change.password.adminStore');

    /************************************ */
    /* Roles                              */
    /************************************ */
    Route::get('/roles/restore/{id}', 'Admin\RoleController@restore')->name('roles.restore');
    Route::get('/roles/getDatatable/{type}', 'Admin\RoleController@getDatatable')->name('roles.getDatatable');
    Route::resource('roles', 'Admin\RoleController');

    /************************************ */
    /* Permissions                        */
    /************************************ */
    Route::get('/permissions/restore/{id}', 'Admin\PermissionController@restore')->name('permissions.restore');
    Route::get('/permissions/getDatatable/{type}', 'Admin\PermissionController@getDatatable')->name('permissions.getDatatable');
    Route::resource('permissions', 'Admin\PermissionController');
});

/********************************************************************************************************************************************/
/* These routes are for template                                                                                                            */
/********************************************************************************************************************************************/
//Only role:SuperAdmin can access these
Route::group(['middleware' => ['auth', 'verified', 'web', 'isSuperAdmin','dbTransaction']], function() {
    /********************************************************** template/basic **********************************************************/
    //for restore functionality (softdelete)
    Route::get('/template/basic/restore/{id}', 'Template\TemplateBasicController@restore')->name('template.basic.restore');
    //datatable for index.blade
    Route::get('/template/basic/getDatatable/{type}', 'Template\TemplateBasicController@getDatatable')->name('template.basic.getDatatable');
    //resource
    Route::resource('/template/basic', 'Template\TemplateBasicController', [
        'as' => 'template' //add prefix to named route
    ]);
});

/********************************************************************************************************************************************/
/* These routes are unused                                                                                                                  */
/********************************************************************************************************************************************/
