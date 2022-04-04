<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique()->nullable();        //alpha_num
            $table->integer('user_number')->nullable();
            $table->string('status',10)->nullable();                 //New user (trial) -> Approved (registered)
            $table->string('name',100)->nullable();                  //
            $table->string('email')->unique()->nullable();           //
            $table->string('designation',50)->nullable();
            $table->string('telephone',20)->nullable();
            $table->string('password')->nullable();
            $table->string('avatar')->default('common/avatar.png');
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->string('created_by',50)->nullable()->default('SYSTEM');    // controller usage
            $table->string('updated_by',50)->nullable()->default('SYSTEM');    // controller usage
        });

        Schema::table('users', function($table) {
            
            //Creating relationship
            $table->bigInteger('supervisor2_fk_users_id')->unsigned()->nullable()->after('id');
            
            $table->foreign('supervisor2_fk_users_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
            
            //Creating relationship
            $table->bigInteger('supervisor1_fk_users_id')->unsigned()->nullable()->after('id');
            
            $table->foreign('supervisor1_fk_users_id')
            ->references('id')
            ->on('users')
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
        Schema::dropIfExists('users');
    }
}
