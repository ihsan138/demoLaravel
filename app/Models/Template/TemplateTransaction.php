<?php

namespace App\Models\Template;

use Illuminate\Database\Eloquent\Model;

class TemplateTransaction extends Model
{
    /**
     * Soft delete
     */
    //use SoftDeletes; 
    //protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    //protected $table = 'template_transactions'; 

    /**
     * Fillable
     */
    protected $fillable = [
        'description','requestor','privatefile',         //reference is sensitive, only handle by system only.
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
        //'description' => 'required|min:5|alpha_spaces',       //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
        'reference' => 'unique:template_transactions,reference',         //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
        'total' => 'required|numeric|min:0.1'
    ];

    /**
     * Validation rules message
     */
    public static $rulesMessages = [
    //    'name1.min' => 'Minimum :attribute is 10',
    //    'name2.min' => 'Minimum :attribute is 10',
    ];
    
    /**
     * Relationship Child Multiple
     */
    public function items(){
        return $this->hasMany('App\Models\Template\TemplateTransactionItem','transaction_reference','reference'); //overide foreign key and local key
    }
    
}
