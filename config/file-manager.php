<?php

use Alexusmai\LaravelFileManager\Services\ConfigService\DefaultConfigRepository;
use Alexusmai\LaravelFileManager\Services\ACLService\ConfigACLRepository;

return [

    /**
     * Set Config repository
     *
     * Default - DefaultConfigRepository get config from this file
     */
    'configRepository' => DefaultConfigRepository::class,

    /**
     * ACL rules repository
     *
     * Default - ConfigACLRepository (see rules in - aclRules)
     */
    //'aclRepository' => ConfigACLRepository::class,
    'aclRepository' => \App\Http\UsersACLRepository::class,      //override ACL rules

    //********* Default configuration for DefaultConfigRepository **************

    /**
     * List of disk names that you want to use
     * (from config/filesystems)
     */
    // change at config/filesystems as well
    //'diskList' => ['my storage'],                 //can only access one disk
    'diskList' => ['common', 'staff', 'user', 'public', 'system', 's3'],            //will list disks on the filemanager view. ACL is defined in app/Http/UsersACLRepository.php

    /**
     * Default disk for left manager
     *
     * null - auto select the first disk in the disk list
     */
    'leftDisk' => null,                             //use with 'windowsConfig' => 3,

    /**
     * Default disk for right manager
     *
     * null - auto select the first disk in the disk list
     */
    'rightDisk' => null,                            //use with 'windowsConfig' => 3,

    /**
     * Default path for left manager
     *
     * null - root directory
     */
    'leftPath' => null,

    /**
     * Default path for right manager
     *
     * null - root directory
     */
    'rightPath' => null,

    /**
     * Image cache ( Intervention Image Cache )
     *
     * set null, 0 - if you don't need cache (default)
     * if you want use cache - set the number of minutes for which the value should be cached
     */
    'cache' => null,

    /**
     * File manager modules configuration
     *
     * 1 - only one file manager window
     * 2 - one file manager window with directories tree module
     * 3 - two file manager windows
     */
    'windowsConfig' => 2,

    /**
     * File upload - Max file size in KB
     *
     * null - no restrictions
     */
    'maxUploadFileSize' => null,

    /**
     * File upload - Allow these file types
     *
     * [] - no restrictions
     */
    'allowFileTypes' => ['pdf'],

    /**
     * Show / Hide system files and folders
     */
    'hiddenFiles' => true,

    /***************************************************************************
     * Middleware
     *
     * Add your middleware name to array -> ['web', 'auth', 'admin']
     * !!!! RESTRICT ACCESS FOR NON ADMIN USERS !!!!
     */
    'middleware' => ['web','auth'],

    /***************************************************************************
     * ACL mechanism ON/OFF
     *
     * default - false(OFF)
     */
    'acl' => true,

    /**
     * Hide files and folders from file-manager if user doesn't have access
     *
     * ACL access level = 0
     */
    'aclHideFromFM' => true,

    /**
     * ACL strategy
     *
     * blacklist - Allow everything(access - 2 - r/w) that is not forbidden by the ACL rules list
     *
     * whitelist - Deny anything(access - 0 - deny), that not allowed by the ACL rules list
     */
    //'aclStrategy' => 'blacklist',         //use blacklist or whitelist
    'aclStrategy' => 'whitelist',           //override ACL rules

    /**
     * ACL Rules cache
     *
     * null or value in minutes
     */
    'aclRulesCache' => null,

    //********* Default configuration for DefaultConfigRepository END **********

    /***************************************************************************
     * ACL rules list - used for default ACL repository (ConfigACLRepository)
     *
     */

    //SEE: app\Http\UsersACLRepository & config/file-manager : aclRepository
    'aclRules' => [
        // null - for not authenticated users
        null => [
            //['disk' => 'public', 'path' => '/', 'access' => 2],
        ],
        // for user with ID = 1
        1 => [
            //['disk' => 'public', 'path' => 'images/arch*.jpg', 'access' => 2],
            //['disk' => 'public', 'path' => 'files/*', 'access' => 1],
        ],
    ],
];
