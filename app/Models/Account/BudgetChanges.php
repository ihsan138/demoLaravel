<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetChanges extends Model
{
    /**
     * Soft delete
     */
    use SoftDeletes; 
    protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    protected $table = 'account_budget_changes';

    /**
     * Fillable
     * - for user input mass assignments only
     * - do not include column entry that is done by backend (created_by, updated_by should be done explicitly)
     */
    protected $fillable = [
        'approved_by_fk_users_id','request_approver_fk_users_id','fk_account_main_id','description','budget_changes','carry_over_balance','on_file','status','date_approved'
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
        // 'account_id' => 'required|min:5|alpha_spaces|unique:account_budget_changes,account_id',         //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
        'fk_account_main_id' => 'required',
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
    public function account_main(){
        return $this->belongsTo('App\Models\Account\Main','fk_account_main_id','id');
    }
    
    /**
     * Relationship Child Single
     */
    public function approved_by(){
        return $this->hasOne('App\Models\Admin\User','id','approved_by_fk_users_id'); //overide foreign key
    }

    /**
     * Relationship Child Single
     */
    public function request_approver(){
        return $this->hasOne('App\Models\Admin\User','id','request_approver_fk_users_id'); //overide foreign key
    }
}
