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
        Schema::create('tblannouncements', function (Blueprint $table) {
            $table->string('announcement_id');
            $table->string('title');
            $table->text('body');
            $table->string('status');
            $table->date('expires_at');
            $table->string('audience');
            $table->string('deleted')->default('0');
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
        Schema::dropIfExists('tblannouncements');
    }
};
