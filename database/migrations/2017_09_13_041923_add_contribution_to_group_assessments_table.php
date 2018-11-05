<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContributionToGroupAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gpasgs', function (Blueprint $table) {
            $table->integer('peer_contribution_percentage')->after('criteria_reference')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gpasgs', function (Blueprint $table) {
            $table->dropColumn('peer_contribution_percentage');
        });
    }
}
