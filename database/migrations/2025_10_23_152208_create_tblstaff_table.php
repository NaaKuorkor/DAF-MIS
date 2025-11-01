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
            $table->foreignId('userid')->constrained('tbluser', 'userid');
            $table->string('staffid')->unique()->primary();
            $table->string('fname', 50);
            $table->string('mname', 50)->nullable();
            $table->string('lname', 50);
            $table->enum('gender', ['M', 'F']);
            $table->string('position', 50);
            $table->string('residence');
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
