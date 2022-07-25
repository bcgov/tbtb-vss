<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteCaseFundings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('case_fundings', function (Blueprint $table) {
            $table->string('deleted_by_user_id')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('case_fundings', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('deleted_by_user_id');
        });
    }
}
