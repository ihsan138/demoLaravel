<?php

namespace App\Models\Template;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;  //for aws s3
use Config;   //for aws s3

class TemplateForm extends Model
{
    
    protected $appends = ['url']; 
    /**
     * Soft delete
     */
    use SoftDeletes; 
    protected $dates = ['deleted_at'];                 // new field to be added in your table

    /**
     * Override table
     */
    protected $table = 'template_forms'; 

    /**
     * Fillable
     * - for user input mass assignments only
     * - do not include column entry that is done by backend (created_by, updated_by should be done explicitly)
     */
    protected $fillable = [
        'userid','string','integer','decimal','datetimepicker','yearpicker','yearmonthpicker','datepicker','timepicker','daterange_start','daterange_end','checkbox','select','s3name','publicfile','privatefile','s3file'
    ];

    /**
     * Validation rules
     * regex: https://laravel.com/docs/master/validation#available-validation-rules
     */
    public static $rules = [
        'string' => 'required|min:5|alpha_spaces',       //alpha_spaces is a custom validation, defined in AppServiceProvider.php as alpha regex in standard Laravel does not accept spaces.
        'integer' => 'required|integer|between:1,10',
        'decimal' => 'required|numeric|between:0.00,0.99',
        'select' => 'required',
    ];

    /**
     * Validation rules message
     */
    public static $rulesMessages = [
    //    'name1.min' => 'Minimum :attribute is 10',
    ];

    
    /**
     * S3
     * This will generate a url that is valid for 20 minutes
     * Only user who uploads to aws s3 can view the files (safer than using storePublicly() at controller@store)
     */
    public function getUrlAttribute()
    {
        return $this->getFileUrl($this->attributes['s3file']);
    }

    private function getFileUrl($key) {
        $s3 = Storage::disk('s3');
        $client = $s3->getDriver()->getAdapter()->getClient();
        $bucket = Config::get('filesystems.disks.s3.bucket');

        $command = $client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => $key
        ]);

        $request = $client->createPresignedRequest($command, '+20 minutes');

        return (string) $request->getUri();
    }
}
