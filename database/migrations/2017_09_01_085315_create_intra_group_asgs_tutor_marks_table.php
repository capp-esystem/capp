<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntraGroupAsgsTutorMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intragpasg_tutor_marks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('asg_id')->unsigned();
            $table->integer('group_id')->unsigned();
            $table->double('marks', 6, 3)->unsigned();
            $table->foreign('asg_id')->references('id')->on('intragpasgs')->onDelete('cascade');
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
        Schema::dropIfExists('intragpasg_tutor_marks');
    }
}
