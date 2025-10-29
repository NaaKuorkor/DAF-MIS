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
            $table->foreignId('user_id')->constrained('tbluser');
            $table->string('student_id')->unique()->primary();
            $table->string('fname');
            $table->string('mname');
            $table->string('lname');
            $table->string('gender');
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
