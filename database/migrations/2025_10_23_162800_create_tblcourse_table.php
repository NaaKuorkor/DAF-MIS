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
        Schema::create('tblcourse', function (Blueprint $table) {
            $table->string('course_id')->unique()->primary();
            $table->string('course_name');
            $table->string('description');
            $table->string('duration', 20);
            $table->char('deleted', 1)->default('0');
            $table->string('createuser');
            $table->timestamp('createdate')->useCurrent();
            $table->string('modifyuser');
            $table->timestamp('modifydate')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblcourse');
    }
};
