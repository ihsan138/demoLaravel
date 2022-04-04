<?php

namespace App\Models\Purchase\Transaction;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorSelection extends Model
{
    /**
     * Soft delete
     */
    use SoftDeletes; 
    protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    protected $table = 'purchase_transaction_vendor_selection'; 

    /**
     * Fillable
     * - for user input mass assignments only
     * - do not include column entry that is done by backend (created_by, updated_by should be done explicitly)
     */
    protected $fillable = [
       'fk_ro_number','quality','services','sole_agent','cost_price','approval_suppliers','contractor_or_panel','delivery','machine_maker','others','others_description'
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
        'fk_ro_number' => 'required|min:5|alpha_spaces|unique:purchase_transaction_main,ro_number',         //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
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
    public function requestor(){
        return $this->hasOne('App\Models\Admin\User','id','requestor_fk_users_id'); //overide foreign key
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
    public function purchase_options_type(){
        return $this->hasOne('App\Models\Purchase\Options\Type','id','fk_purchase_options_type_id'); //overide foreign key
    }

    /**
     * Relationship Child Single
     */
    public function purchase_vendor_selection_criteria(){
        return $this->hasOne('App\Models\Purchase\Options\Type','id','fk_purchase_options_type_id'); //overide foreign key
    }
}
