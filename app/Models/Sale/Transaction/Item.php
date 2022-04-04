<?php

namespace App\Models\Sale\Transaction;

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
    protected $table = 'sale_transaction_item'; 

    /**
     * Fillable
     * - for user input mass assignments only
     * - do not include column entry that is done by backend (created_by, updated_by should be done explicitly)
     */
    protected $fillable = [
       'fk_sale_order_number','no','item_name','sold_quantity','quantity_unit_of_measure','unit_price'
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
        // 'sale_order_number' => 'required|min:5|alpha_num_spaces|unique:sale_transaction_item,sale_order_number',         //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
    ];

    /**
     * Validation rules message
     */
    public static $rulesMessages = [
    //    'name1.min' => 'Minimum :attribute is 10',
    ];
}
