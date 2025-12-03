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
            $table->enum('user_type', ['STA', 'STU', 'ADM']);
            $table->string('password')->nullable();
            $table->string('phone', 15);
            $table->char('deleted', 1)->default('0');
            $table->string('picture', 255)->nullable();
            $table->char('status', 1)->default('1');
            $table->string('unit', 10)->nullable();
            $table->string('account_code', 20)->default('100');
            $table->char('country', 3)->default('GH');
            $table->string('createuser', 255)->nullable();
            $table->timestamp('createdate')->useCurrent();
            $table->string('modifyuser', 255)->nullable();
            $table->timestamp('modifydate')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('email_verified_at')->nullable();
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
