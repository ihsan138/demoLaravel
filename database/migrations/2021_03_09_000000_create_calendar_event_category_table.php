<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarEventCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_event_category', function (Blueprint $table) {
            $table->id();
            $table->string('name',50)->nullable();
            $table->string('borderColor',25)->nullable();
            $table->string('backgroundColor',25)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->string('created_by',50)->nullable()->default('SYSTEM');           // string
            $table->string('updated_by',50)->nullable()->default('SYSTEM');            // string
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_event_category');
    }
}
