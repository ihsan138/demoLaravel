<?php

namespace App\Models\Template;

use Illuminate\Database\Eloquent\Model;

class TemplateTransactionNo extends Model
{
    /**
     * Soft delete
     */
    //use SoftDeletes; 
    //protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    //protected $table = 'templates_transaction_nos'; 

    /**
     * Fillable
     */
    protected $fillable = [
    //   'name',
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
        'reference' => 'required|unique:template_transaction_nos,reference',         //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
    ];

    /**
     * Validation rules message
     */
    public static $rulesMessages = [
    //    'name1.min' => 'Minimum :attribute is 10',
    //    'name2.min' => 'Minimum :attribute is 10',
    ];
}
