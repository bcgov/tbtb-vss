<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseAuditTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_audit_types', function (Blueprint $table) {
            $table->id();

            $table->string('area_of_audit_code')->default(1);
            $table->foreign('area_of_audit_code')->references('area_of_audit_code')->on('area_of_audits')->onDelete('cascade');

            $table->bigInteger('incident_id')->default(1);
            $table->foreign('incident_id')->references('incident_id')->on('incidents')->onDelete('cascade');

            $table->char('audit_type')->default('P');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_audit_types');
    }
}
