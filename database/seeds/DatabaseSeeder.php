<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=UserSeeder

        //php artisan db:seed
        $this->call('InitSeeder');  //user defined in here
        $this->call('TemplateBasicSeeder');
    
        /***************************************************************/
        $this->call('CalendarEventCategorySeeder');
        $this->call('CalendarCalibrationCategorySeeder');
        
        /***************************************************************/
    }
}
