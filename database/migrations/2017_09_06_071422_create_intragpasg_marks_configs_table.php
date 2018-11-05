<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntragpasgMarksConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intragpasg_marks_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('asg_id')->unsigned()->unique();
            $table->enum('pa_score_adjust_method', ['default', 'power', 'percentage']);
            $table->double('pa_score_adjust_value', 5, 2)->nullable();
            $table->integer('max_cap')->default(100);
            $table->integer('penalty')->default(0);
            $table->foreign('asg_id')->references('id')->on('intragpasgs')->onDelete('cascade');
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
        Schema::dropIfExists('intragpasg_marks_configs');
    }
}
