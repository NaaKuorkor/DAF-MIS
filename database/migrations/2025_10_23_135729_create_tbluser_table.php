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
        Schema::create('tbluser', function (Blueprint $table) {
            $table->string('userid')->unique()->primary();
            $table->string('email', 255)->unique();
            $table->enum('user_type', ['STA', 'STU']);
            $table->string('password');
            $table->string('phone', 15)->nullable();
            $table->char('deleted', 1)->default('0');
            $table->string('createuser', 255)->nullable();
            $table->timestamp('createdate')->useCurrent();
            $table->string('modifyuser', 255)->nullable();
            $table->timestamp('modifydate')->useCurrent()->useCurrentOnUpdate();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbluser');
    }
};
