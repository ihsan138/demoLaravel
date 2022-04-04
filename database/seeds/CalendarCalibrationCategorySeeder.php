<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CalendarCalibrationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('calendar_calibration_category')->insert([
            [
                'name' => 'Renewal License (software)',
                'borderColor' => 'aqua',
                'backgroundColor' => '#00C0EF',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Uncategorised',
                'borderColor' => 'red',
                'backgroundColor' => '#C34232',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
