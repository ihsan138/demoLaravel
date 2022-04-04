<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CalendarEventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('calendar_event_category')->insert([
            [
                'name' => 'Meeting',
                'borderColor' => 'yellow',
                'backgroundColor' => '#F39C12',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Seminar/Training',
                'borderColor' => 'blue',
                'backgroundColor' => '#0073B7',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Renewal License (software)',
                'borderColor' => 'aqua',
                'backgroundColor' => '#00C0EF',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Visit Supplier',
                'borderColor' => 'green',
                'backgroundColor' => '#00A65A',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Delivery',
                'borderColor' => 'light-blue',
                'backgroundColor' => '#3C8DBC',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Public Holiday',
                'borderColor' => 'purple',
                'backgroundColor' => '#605CA8',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'External visitor / Customer Visit',
                'borderColor' => 'teal',
                'backgroundColor' => '#39CCCC',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'APQP Event',
                'borderColor' => 'orange',
                'backgroundColor' => '#FF851B',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'External Event',
                'borderColor' => 'fuchsia',
                'backgroundColor' => '#F012BE',
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
