<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_event', function (Blueprint $table) {
            $table->id();
            $table->string('title',150)->nullable();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->string('display',25)->nullable()->default('block');
            $table->timestamps();
            $table->string('created_by',50)->nullable()->default('SYSTEM');    // controller usage
            $table->string('updated_by',50)->nullable()->default('SYSTEM');    // controller usage
        });
        
        Schema::table('calendar_event', function($table) {
            //Creating relationship
            $table->bigInteger('calendar_event_category_id')->unsigned()->nullable()->after('id');
            
            $table->foreign('calendar_event_category_id')
            ->references('id')
            ->on('calendar_event_category')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_event');
    }
}
