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
        Schema::create('tbluser_module_priviledges', function (Blueprint $table) {
            $table->string('userid', 50);
            $table->char('modid', 5);
            $table->boolean('mod_create', 1)->default('0');
            $table->boolean('mod_read', 1)->default('0');
            $table->boolean('mod_update', 1)->default('0');
            $table->boolean('mod_delete', 1)->default('0');
            $table->boolean('mod_report', 1)->default('0');
            $table->string('createuser', 50)->nullable();
            $table->timestamp('createdate')->useCurrent();
            $table->string('modifyuser', 50)->nullable();
            $table->timestamp('modifydate')->useCurrent()->useCurrentOnUpdate();
            $table->string('mod_icon', 20)->nullable();
            $table->string('pmod_id', 20)->nullable();

            $table->foreign('userid')->references('userid')->on('tbluser');
            $table->foreign('modid')->references('modid')->on('tblmodule');
            $table->primary(['userid', 'modid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbluser_module_priviledges');
    }
};
