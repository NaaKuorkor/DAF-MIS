<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tblstudent', function (Blueprint $table) {
            $table->string('userid');
            $table->foreign('userid')->references('userid')->on('tbluser');
            $table->string('studentid')->unique()->primary();
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('lname');
            $table->enum('gender', ['M', 'F']);
            $table->tinyInteger('age');
            $table->string('residence');
            $table->string('referral')->nullable();
            $table->string('employment_status');
            $table->enum('certificate', ['Y', 'N']);
            $table->string('payment')->nullable();
            $table->string('job_preference')->nullable();
            $table->string('createuser')->default('system');
            $table->timestamp('createdate')->useCurrent();
            $table->string('modifyuser')->nullable()->default(null);
            $table->timestamp('modifydate')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblstudent');
    }
};
