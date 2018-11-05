<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGpasgUserMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gpasg_user_marks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('asg_id')->unsigned();
            $table->integer('from_id')->unsigned();
            $table->integer('group_id')->unsigned();
            $table->integer('marks')->unsigned();
            $table->foreign('asg_id')->references('id')->on('gpasgs')->onDelete('cascade');
            $table->foreign('from_id')->references('id')->on('courses_users')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gpasg_user_marks');
    }
}
