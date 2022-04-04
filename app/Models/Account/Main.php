<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Main extends Model
{
    /**
     * Soft delete
     */
    use SoftDeletes; 
    protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    protected $table = 'account_main'; 

    /**
     * Fillable
     * - for user input mass assignments only
     * - do not include column entry that is done by backend (created_by, updated_by should be done explicitly)
     */
    protected $fillable = [
       'fk_company_department_id','pic_fk_users_id','name','description','status'
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
        'name' => 'required|min:3|alpha_num_spaces|unique:account_main,name',         //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
    ];

    /**
     * Validation rules message
     */
    public static $rulesMessages = [
    //    'name1.min' => 'Minimum :attribute is 10',
    ];
    
    /**
     * Relationship Parent
     */
    public function company_department(){
        return $this->belongsTo('App\Models\Company\Department','fk_company_department_id','id');
    }
    
    /**
     * Relationship Child Single
     */
    public function pic(){
        return $this->hasOne('App\Models\Admin\User','id','pic_fk_users_id'); //overide foreign key
    }

    /**
     * Relationship Child Multiple
     */
    public function budget_changes(){
        return $this->hasMany('App\Models\Account\BudgetChanges','fk_account_main_id','id'); //overide foreign key and local key
    }

    /**
     * Relationship Child Multiple
     */
    public function purchase_transaction_main(){
        return $this->hasMany('App\Models\Purchase\Transaction\Main','fk_account_main_id','id'); //overide foreign key and local key
    }
}
