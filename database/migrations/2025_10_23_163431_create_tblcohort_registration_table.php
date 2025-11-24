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
        Schema::create('tblcohort_registration', function (Blueprint $table) {
            $table->string('studentid');
            $table->string('cohort_id');
            $table->foreign('studentid')->references('studentid')->on('tblstudent');
            $table->foreign('cohort_id')->references('cohort_id')->on('tblcohort');
            $table->primary(['studentid', 'cohort_id']);
            $table->char('is_completed')->default('0');
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
        Schema::dropIfExists('tblcohort_registration');
    }
};
