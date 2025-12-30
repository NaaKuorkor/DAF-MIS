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
        Schema::create('tblcohort', function (Blueprint $table) {
            $table->string('cohort_id')->unique();
            $table->string('course_id');
            $table->foreign('course_id')->references('course_id')->on('tblcourse');


            $table->primary('cohort_id');

            $table->string('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('student_limit');
            $table->char('is_completed')->default(0);
            $table->char('deleted', 1)->default('0');
            $table->string('createuser')->nullable()->default(null);
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
        Schema::dropIfExists('tblcohort');
    }
};
