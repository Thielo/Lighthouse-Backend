<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('lock_reason')->nullable();
            $table->boolean('sticky')->default(0);
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('locked_by')->unsigned()->nullable();
            $table->dateTime('locked_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('threads', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('locked_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}
