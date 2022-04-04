<?php

namespace App\Models\Calendar\Event;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendar extends Model
{
    /**
     * Soft delete
     */
    //use SoftDeletes; 
    //protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    protected $table = 'calendar_event'; 

    /**
     * Fillable
     * - for user input mass assignments only
     * - do not include column entry that is done by backend (created_by, updated_by should be done explicitly)
     */
    protected $fillable = [
       'title', 'start', 'end','calendar_event_category_id'
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
        'title' => 'required|min:5|string',       //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
        'daterange' => 'required',
    ];

    public static $rules2 = [
        'title' => 'required|min:5|string',       //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
        'start_date' => 'required',
        'end_date' => 'required',
    ];

    /**
     * Validation rules message
     */
    public static $rulesMessages = [
    //    'name1.min' => 'Minimum :attribute is 10',
    //    'name2.min' => 'Minimum :attribute is 10',
    ];
    
    public static $rulesMessages2 = [
    //    'name1.min' => 'Minimum :attribute is 10',
    //    'name2.min' => 'Minimum :attribute is 10',
    ];
}
