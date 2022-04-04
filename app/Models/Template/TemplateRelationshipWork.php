<?php

namespace App\Models\Template;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateRelationshipWork extends Model
{
    /**
     * Soft delete
     */
    //use SoftDeletes; 
    //protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    //protected $table = 'template_relationship_works'; 

    /**
     * Fillable
     * - for user input mass assignments only
     * - do not include column entry that is done by backend (created_by, updated_by should be done explicitly)
     */
    protected $fillable = [
        'name','description'
     ];
 
     /**
      * Validation rules
      * regex: https://laravel.com/docs/master/validation#available-validation-rules
      */
     public static $rules = [
         'name' => 'required|min:5|alpha_spaces|unique:template_relationship_works,name',         //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
     ];
 
     /**
      * Validation rules message
      */
     public static $rulesMessages = [
     //    'name1.min' => 'Minimum :attribute is 10',
     ];
     
     /**
      * Relationship Many
      */
     public function passenger()
     {
         //return $this->belongsToMany(RelatedModel, pivot_table_name, foreign_key_of_current_model_in_pivot_table, foreign_key_of_other_model_in_pivot_table);
         return $this->belongsToMany('App\TemplateRelationshipPassenger','template_relationship_passenger_has_work','work_id','passenger_id');
     }
 
}
