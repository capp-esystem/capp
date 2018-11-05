<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGpasgMarksConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gpasg_marks_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('asg_id')->unsigned()->unique();
            $table->enum('moderate_method', ['high_only', 'high_low']);
            $table->integer('upper_limit');
            $table->integer('lower_limit')->default(0);
            $table->integer('penalty')->default(0);
            $table->foreign('asg_id')->references('id')->on('gpasgs')->onDelete('cascade');
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
        Schema::dropIfExists('gpasg_marks_configs');
    }
}
