<?php

namespace App\Models\Template;

use Illuminate\Database\Eloquent\Model;

class TemplateTransactionItem extends Model
{
    /**
     * Soft delete
     */
    //use SoftDeletes; 
    //protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    //protected $table = 'template_transaction_items'; 

    /**
     * Fillable
     */
    protected $fillable = [
        'item_no', 'item_name', 'quantity', 'unit_of_measure', 'price', 'discount'
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
    // since item creation is done in transaction controller, we use validation at html side.
    //    'item_name' => 'required|min:5|alpha_spaces',       //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
    //    'quantity' => 'required|integer|min:1',
    //    'unit_of_measure' => 'required|alpha_spaces',
    //    'price' => 'required|decimal',
    //    'discount' => 'required|discount'
    ];

    /**
     * Validation rules message
     */
    //public static $rulesMessages = [
    //    'name1.min' => 'Minimum :attribute is 10',
    //    'name2.min' => 'Minimum :attribute is 10',
    //];
    
    /**
     * Relationship Parent
     */
      public function transaction(){
        return $this->belongsTo('App\Models\Template\TemplateTransaction');
    }
}
