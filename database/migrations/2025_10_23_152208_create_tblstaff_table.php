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
        Schema::create('tblstaff', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->conatrained('tbluser');
            $table->string('staff_id')->unique();
            $table->string('fname', 50);
            $table->string('mname', 50)->nullable();
            $table->string('lname', 50);
            $table->string('gender', 10);
            $table->strint('position', 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblstaff');
    }
};
