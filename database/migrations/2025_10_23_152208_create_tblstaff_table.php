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
            $table->string('userid');
            $table->foreign('userid')->references('userid')->on('tbluser');
            $table->string('staffid')->unique()->primary();
            $table->string('fname', 50);
            $table->string('mname', 50)->nullable();
            $table->string('lname', 50);
            $table->enum('gender', ['M', 'F']);
            $table->string('position', 50);
            $table->string('residence');
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
        Schema::dropIfExists('tblstaff');
    }
};
