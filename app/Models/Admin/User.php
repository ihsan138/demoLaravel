<?php

namespace App\Models\Admin;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasRoles;

    /**
     * Soft delete
     */
    use SoftDeletes; 
    protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    //protected $table = 'users'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status', 'name', 'username', 'email', 'password', 'telephone', 'avatar', 'created_by'
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
        ///required is omitted because different forms - registration & edit profile
        'name' => 'required|min:5|string',       //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
        'username' => 'min:2|alpha|unique:users,username',
        'password' => 'min:8',
        'password_confirmation' => 'same:password|min:8',
        'email' => 'unique:users,email',
        'telephone' => 'min:10',
        'avatar' => 'mimes:jpeg,jpg,png',
        // 'created_by' => '',
    ];

    public static $rules2 = [
        'username' => 'min:5|alpha_num|unique:users,username',
        'telephone' => 'min:10',
    ];
    /**
     * Validation rules message
     */
    public static $rulesMessages = [
    //    'name1.min' => 'Minimum :attribute is 10',
    //    'name2.min' => 'Minimum :attribute is 10',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
    * Send the password reset notification.
    *
    * @param  string  $token
    * @return void
    */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new Notifications\MailResetPasswordNotification($token));
    }

    /**
     * Relationship Parent
      */
    public function company_account_pic(){
        return $this->belongsTo('App\Models\Account\Main','pic_fk_users_id','id');
    }

    /**
     * Relationship Child Single
     */
    public function company_department(){
        return $this->hasOne('App\Models\Company\Department','id','fk_company_department_id'); //overide foreign key
    }

    /**
     * Relationship Child Single
     */
    public function supervisor1(){
        return $this->hasOne('App\Models\Admin\User','id','supervisor1_fk_users_id'); //overide foreign key
    }

    /**
     * Relationship Child Single
     */
    public function supervisor2(){
        return $this->hasOne('App\Models\Admin\User','id','supervisor2_fk_users_id'); //overide foreign key
    }

    
}
