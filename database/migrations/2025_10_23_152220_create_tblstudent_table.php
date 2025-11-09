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
            $table->string('residence');
            $table->string('referral')->nullable();
            $table->foreign('course_id')->references('course_id')->on('tblcourse');
            $table->string('employment_status');
            $table->string('qualification')->nullable();
            $table->string('certificate');
            $table->string('payment')->nullable();
            $table->string('job_preference')->nullable();
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
