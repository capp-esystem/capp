<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntraGroupAsgsUserMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intragpasg_user_marks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('asg_id')->unsigned();
            $table->integer('from_id')->unsigned();
            $table->integer('to_id')->unsigned();
            $table->integer('criteria_id')->unsigned();
            $table->integer('marks');
            $table->foreign('asg_id')->references('id')->on('intragpasgs')->onDelete('cascade');
            $table->foreign('criteria_id')->references('id')->on('criterias')->onDelete('cascade');
            $table->foreign('from_id')->references('id')->on('courses_users')->onDelete('cascade');
            $table->foreign('to_id')->references('id')->on('courses_users')->onDelete('cascade');
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
        Schema::dropIfExists('intragpasg_user_marks');
    }
}
