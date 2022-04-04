<?php

namespace App\Models\Purchase\Transaction;

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
    protected $table = 'purchase_transaction_main'; 

    /**
     * Fillable
     * - for user input mass assignments only
     * - do not include column entry that is done by backend (created_by, updated_by should be done explicitly)
     */
    protected $fillable = [
        'approved_by_fk_users_id','request_approver_fk_users_id','fk_purchase_options_type_id','requestor_fk_users_id','fk_account_main_id','fk_company_department_id','fk_purchase_options_type_steps_no','status','do_number','grn_number','date_request_for_quotation','date_delivery_required','date_purchase_request','date_delivery_order','description','special_instructions','purchase_justifications','capex_item','purchase_class','vendor1','vendor1_currency','vendor1_forex','vendor2','vendor2_currency','vendor2_forex','vendor3','vendor3_currency','vendor3_forex','vendor4','vendor4_currency','vendor4_forex','vendor_selection','one_vendor_justification','vendor_quotation_no','summary_vendor_selected_subtotal','summary_vendor_selected_total_in_myr','vendor_selected_terms','vendor_remarks','printed_documents','file_proforma_invoice','file_tax_invoice','file_delivery_order','remarks','date_printed','date_submitted_to_vendor','date_submitted_to_account','date_payment_made','date_items_received','date_do_received','date_documents_uploaded','date_grn_updated','date_completed','date_approved',
        // 'case','ro_number','po_number'
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
        // 'ro_number' => 'required|min:5|alpha_num_spaces|unique:purchase_transaction_main,ro_number',         //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
        'description' => 'required',
        'fk_company_department_id' => 'required',
        // 'vendor_selection' => 'required'
    ];

    public static $rules2 = [
        // 'ro_number' => 'required|min:5|alpha_num_spaces|unique:purchase_transaction_main,ro_number',         //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
        // 'description' => 'required',
        // 'fk_company_department_id' => 'required',
        // 'vendor_selection' => 'required'
    ];

    /**
     * Validation rules message
     */
    public static $rulesMessages = [
        //    'name1.min' => 'Minimum :attribute is 10',
    ];
    
    public static $rulesMessages2 = [
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
    public function approved_by(){
        return $this->hasOne('App\Models\Admin\User','id','approved_by_fk_users_id'); //overide foreign key
    }

    /**
     * Relationship Child Single
     */
    public function request_approver(){
        return $this->hasOne('App\Models\Admin\User','id','request_approver_fk_users_id'); //overide foreign key
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
    public function purchase_transaction_vendor(){
        return $this->hasOne('App\Models\Purchase\Transaction\Vendor','fk_ro_number','ro_number'); //overide foreign key
    }

    /**
     * Relationship Child Single
     */
    public function purchase_transaction_vendor_selection(){
        return $this->hasOne('App\Models\Purchase\Transaction\VendorSelection','fk_ro_number','ro_number'); //overide foreign key
    }
    
    /**
     * Relationship Child Multiple
     */
    public function items(){
        return $this->hasMany('App\Models\Purchase\Transaction\Item','fk_ro_number','ro_number'); //overide foreign key and local key
    }
}
