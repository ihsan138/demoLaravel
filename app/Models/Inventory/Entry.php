<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
{
    /**
     * Soft delete
     */
    use SoftDeletes; 
    protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    protected $table = 'inventory_entry'; 

    /**
     * Fillable
     * - for user input mass assignments only
     * - do not include column entry that is done by backend (created_by, updated_by should be done explicitly)
     */
    protected $fillable = [
        'fk_purchase_transaction_main_ro_number','fk_inventory_sku_id','purchase_transaction_item_no','batch_number','entry_quantity','remarks'
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
        // 'name' => 'required|min:5|alpha_spaces|unique:inventory_entry,name',         //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
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
    public function inventory_sku(){
        return $this->belongsTo('App\Models\Inventory\Sku','fk_inventory_sku_id','id');
    }
}
