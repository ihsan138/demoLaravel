<?php

namespace App\Models\Template;

use Illuminate\Database\Eloquent\Model;

class TemplateRelationshipPassenger extends Model
{
    /**
     * Soft delete
     */
    //use SoftDeletes; 
    //protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    //protected $table = 'template_relationship_passengers'; 

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
        'name' => 'required|min:5|alpha_spaces|unique:template_relationship_passengers,name',         //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
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
        // return $this->belongsTo(Post::class, 'foreign_key', 'owner_key');
        return $this->belongsTo('App\TemplateRelationshipCar');
    }

    /**
     * Relationship Many
     */
    public function work()
    {
        //return $this->belongsToMany(RelatedModel, pivot_table_name, foreign_key_of_current_model_in_pivot_table, foreign_key_of_other_model_in_pivot_table);
        return $this->belongsToMany('App\TemplateRelationshipWork','template_relationship_passenger_has_work','passenger_id','work_id');
    }
}
