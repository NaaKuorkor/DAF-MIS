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
        Schema::create('tblmodule', function (Blueprint $table) {
            $table->string('mod_id')->unique()->primary();
            $table->char('mod_position', 10);
            $table->string('mod_name', 20);
            $table->string('mod_label', 20);
            $table->string('mod_url', 50);
            $table->integer('is_child')->default(0);
            $table->string('pmod_id', 10)->nullable();
            $table->integer('has_child')->default(0);
            $table->string('icon_class', 125);
            $table->char('mod_status', 1)->default('1');
            $table->string('mod_icon', 50);
            $table->string('createuser');
            $table->timestamp('createdate')->useCurrent();
            $table->string('modifyuser');
            $table->timestamp('modifydate')->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblmodule');
    }
};
