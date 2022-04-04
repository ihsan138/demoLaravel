<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public static $updateRules = [
        'logo' => 'mimes:jpeg,jpg,png',
    ];
}
