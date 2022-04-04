<?php

namespace App\Models\Template;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateRelationshipOwner extends Model
{
    /**
     * Soft delete
     */
    //use SoftDeletes; 
    //protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    //protected $table = 'template_relationship_owners'; 

    /**
     * Fillable
     */
    protected $fillable = [
       'name','car_id'
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
        'name' => 'required|min:5|alpha_spaces|unique:template_relationship_owners,name',         //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
        'car_name' => 'required|unique:template_relationship_owners,car_id'
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
    public function car(){
        //return $this->belongsTo(User::class, 'foreign_key', 'owner_key');
        return $this->belongsTo('App\TemplateRelationshipCar');
    }
}
