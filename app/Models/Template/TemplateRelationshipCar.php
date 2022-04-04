<?php

namespace App\Models\Template;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateRelationshipCar extends Model
{
    /**
     * Soft delete
     */
    //use SoftDeletes; 
    //protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    //protected $table = 'template_relationship_cars'; 

    /**
     * Fillable
     */
    protected $fillable = [
       'name'
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
        'name' => 'required|min:5|alpha_spaces|unique:template_relationship_cars,name',         //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
    ];

    /**
     * Validation rules message
     */
    public static $rulesMessages = [
    //    'name1.min' => 'Minimum :attribute is 10',
    ];

    /**
     * Relationship Child Single
     */
    public function owner(){
    //    return $this->hasOne('App\TemplateRelationshipOwner'); //automatically assume foreign key is template_relationship_owner_id
        return $this->hasOne('App\TemplateRelationshipOwner','car_id'); //overide foreign key
    }

    /**
     * Relationship Child Multiple
     */
    public function passengers(){
        return $this->hasMany('App\TemplateRelationshipPassenger','car_id'); //overide foreign key
    }

}
