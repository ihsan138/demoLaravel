<?php

namespace App\Listeners;

//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Queue\InteractsWithQueue;
use Spatie\Backup\Events\BackupZipWasCreated;
use App\Backup;
use Log;
use Storage;
use Spatie\Backup\Helpers\Format;


class StoreZipPath
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BackupZipWasCreated  $event
     * @return void
     */
    public function handle(BackupZipWasCreated $event)
    {
        //Log::info(print_r($event, true));

        // Example event->pathToZip : D:\laragon\www\laratest\storage\app/backup-temp\temp\2020-04-10-19-24-46.zip
        //Get name
        $filePath = $event->pathToZip;
        $filename = explode('temp', $filePath);                  //cannot use this path for retrieving file size, specifically in this event-listener
        $name = ltrim($filename[2],' \ ');                       //to retrieve the filename
        $filename2 = explode('app', $filePath);                  //use this path instead

        //Get size
        //$bytes = Storage::size(env('APP_NAME').$filename[2]);  //LaraTest/2020-04-10-18-16-17.zip, physically in storage/app/LaraTest/[filename]
        $bytes = Storage::size($filename2[1]);                   //We take the one in backup-dump first, it will later then transferred to storage/app/LaraTest

        $size = Format::humanReadableSize($bytes);
        
        //save data
        $backup = new Backup;
        $backup->name = $name;
        $backup->size = $size;
        $saved = $backup->save();     //automatically update timestamp fields 
    }
}
