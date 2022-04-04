<?php

namespace App\Http;

use Alexusmai\LaravelFileManager\Services\ACLService\ACLRepository;
use Auth;
use Log;

class UsersACLRepository implements ACLRepository
{
    /**
     * Get user ID
     *
     * @return mixed
     */
    public function getUserID()
    {
        return \Auth::id();
    }

    /**
     * Get ACL rules list for user
     *
     * @return array
     */
    public function getRules(): array
    {
        /***************************************************************************
        1. We override this with app\Http\UsersACLRepository & config/file-manager : aclRepository
        2. For each registered user, a new folder is created with his username(in folder /users).
        3. Allow users access only to their folders.
        4. An administrator with ID = 1, is allowed access to all folders.

        Since we use in config/file-manager, 'aclStrategy' => 'whitelist', then we return only allowable folders and files here.

        1 it's user ID
        null - for not authenticated user

        'disk' => 'disk-name'

        Examples:
        'path' => 'folder-name'
        'path' => 'folder1*' - select folder1, folder12, folder1/sub-folder, ...
        'path' => 'folder2/*' - select folder2/sub-folder,... but not select folder2 !!!
        'path' => 'folder-name/file-name.jpg'
        'path' => 'folder-name/*.jpg'

        * - wildcard
        access: 0 - deny, 1 - read, 2 - read/write
        ***************************************************************************/   
        $disklist = [];

        //SuperAdmin & WebAdmin can access everything \O/
        if(Auth::user()->hasAnyRole('SuperAdmin','WebAdmin')){   
            return [
                ['disk' => 'system', 'path' => '*', 'access' => 2],
                ['disk' => 'common', 'path' => '*', 'access' => 2],
                ['disk' => 'staff', 'path' => '*', 'access' => 2],
                ['disk' => 'user', 'path' => '*', 'access' => 2],
                ['disk' => 'public', 'path' => '*', 'access' => 2],
                ['disk' => 's3', 'path' => '*', 'access' => 2],
            ];
        }

        // Every authorized user
        if(Auth::check()){
            
            //Every authorized  user, excluding intern
            //Intern can only read
            if(Auth::user()->hasRole('Intern')){        
                $new_disk = array('disk'=>'common','path'=>'*','access'=>1);
                array_push($disklist, $new_disk);
                //Everyone
                $new_disk = array('disk'=>'public','path'=>'*','access'=>1);
                array_push($disklist, $new_disk);
            }else{  
                $new_disk = array('disk'=>'common','path'=>'*','access'=>2);
                array_push($disklist, $new_disk);
                $new_disk = array('disk'=>'staff','path'=>'*','access'=>2);
                array_push($disklist, $new_disk);
                $new_disk = array('disk'=>'public','path'=>'*','access'=>2);
                array_push($disklist, $new_disk);
            }   

            //Auth->id() only                                   
            $current_user_number = Auth::user()->user_number;
            
            $new_disk = array('disk'=>'user','path'=>'/','access'=>1);                                 // main folder - read
            array_push($disklist, $new_disk);
            $new_disk = array('disk'=>'user','path'=>'user','access'=>1);                                 // main folder - read
            array_push($disklist, $new_disk);
            $new_disk = array('disk'=>'user','path'=>'user/'. $current_user_number,'access'=>1);       // only read, user cannot delete his/her folder
            array_push($disklist, $new_disk);
            $new_disk = array('disk'=>'user','path'=>'user/'. $current_user_number .'/*','access'=>2); // read and write, * => any type of folders or files
            array_push($disklist, $new_disk);
        }

        return $disklist;
        

        // return [
        //     //depends on the 'diskList' option from config/file-manager.php
        //     //first disk: private disk
        //     ['disk' => 'private', 'path' => '/', 'access' => 1],                                          // main folder - read
        //     ['disk' => 'private', 'path' => 'users', 'access' => 1],                                      // only read
        //     ['disk' => 'private', 'path' => 'users/'. $current_username, 'access' => 1],                  // only read, user cannot delete his/her folder
        //     ['disk' => 'private', 'path' => 'users/'. $current_username .'/*', 'access' => 2],            // read and write, * => any type of folders or files

        //     //second disk: public disk 
        //     ['disk' => 'public', 'path' => '/', 'access' => 1],                                          // main folder - read
        //     ['disk' => 'public', 'path' => 'users', 'access' => 1],                                       // only read
        //     //['disk' => 'public', 'path' => 'users/'. $current_username, 'access' => 1],                 // only read, user cannot delete his/her folder
        //     ['disk' => 'public', 'path' => 'users/*', 'access' => 2],                                     // read and write, * => any type of folders or files

        // ];
    }
}
