<?php

namespace App\Models\Purchase\Transaction;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    /**
     * Soft delete
     */
    // use SoftDeletes; 
    protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    protected $table = 'purchase_transaction_item'; 

    /**
     * Fillable
     * - for user input mass assignments only
     * - do not include column entry that is done by backend (created_by, updated_by should be done explicitly)
     */
    protected $fillable = [
       'fk_ro_number','no','item_name','ordered_quantity','received_quantity','added_to_inventory_quantity','quantity_unit_of_measure','vendor1_price','vendor2_price','vendor3_price','vendor4_price','vendor_selected_index'
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
        // 'ro_number' => 'required|min:5|alpha_spaces|unique:purchase_transaction_main,ro_number',         //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
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
    public function purchase_transaction_main(){
        return $this->belongsTo('App\Models\Purchase\Transaction\Main','fk_ro_number','ro_number');
    }
}
