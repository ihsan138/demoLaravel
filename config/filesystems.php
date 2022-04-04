<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    // Disk name | Physical Path                    | File Manager Accesibility
    // system    | storage/app/system/{purchasing}  | Only by SuperAdmin & Admin
    // common    | storage/app/common               | Every authorized user
    // staff     | storage/app/staff                | Every authorized  user, excluding intern
    // user      | storage/app/user/{user_id}       | Auth->id() only
    // public    | storage/app/public               | Everyone

    'disks' => [

        'local' => [                               //just to change the name in file manager view
            'driver' => 'local',
            'root' => storage_path('app'),   //physically refers to storage/app/system
        ],

        //We define this in reference to filemanager module
        'system' => [                               //just to change the name in file manager view
            'driver' => 'local',
            'root' => storage_path('app/system'),   //physically refers to storage/app/system
        ],
        'common' => [                               //just to change the name in file manager view
            'driver' => 'local',
            'root' => storage_path('app/common'),   //physically refers to storage/app/system
        ],
        'user' => [                                 //just to change the name in file manager view
            'driver' => 'local',
            'root' => storage_path('app'),          //physically refers to storage/app, later define in UsersACLRepository for path
        ],
        'private' => [                              //just to change the name in file manager view
            'driver' => 'local',
            'root' => storage_path('app'),          //physically refers to storage/app, later define in UsersACLRepository for path
        ],
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),    //physically stored at 
            'url' => env('APP_URL').'/storage',      //can be accessed via URL
            'visibility' => 'public',
        ],
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'endpoint' => env('AWS_URL'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
    
    ],

];
