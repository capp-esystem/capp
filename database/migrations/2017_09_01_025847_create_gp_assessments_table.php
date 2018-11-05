<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGpAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gpasgs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->unsigned();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->longText('criteria_reference')->nullable();
            $table->enum('response_type', ['questions', 'comments'])->default('questions');
            $table->integer('response_no_required')->unsigned()->default(0);
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
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
        Schema::dropIfExists('gpasgs');
    }
}
