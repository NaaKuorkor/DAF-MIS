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
        Schema::create('tblcourse_registration', function (Blueprint $table) {
            $table->string('transid')->unique()->primary();
            $table->foreign('course_id')->references('course_id')->on('tblcourse');
            $table->foreign('studentid')->references('studentid')->on('tblstudent');
            $table->char('is_completed')->default(0);
            $table->string('createuser');
            $table->timestamp('createdate')->useCurrent();
            $table->string('modifyuser')->default(null);
            $table->timestamp('modifydate')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblcourse_registration');
    }
};
