<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SkuItems extends Model
{
    /**
     * Soft delete
     */
    use SoftDeletes; 
    protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    protected $table = 'inventory_sku_items'; 

    /**
     * Fillable
     * - for user input mass assignments only
     * - do not include column entry that is done by backend (created_by, updated_by should be done explicitly)
     */
    protected $fillable = [
       'fk_items_outgoing_type_id','fk_inventory_sku_id','name','outgoing_inspection','remarks'
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
        'name' => 'required|min:5|string|unique:inventory_sku_items,name',         //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
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
    
    /**
     * Relationship Child Single
     */
    public function inventory_sku_items_outgoing_type(){
        return $this->hasOne('App\Models\Inventory\SkuItemsOutgoingType','id','fk_items_outgoing_type_id'); //overide foreign key
    }
    
}
