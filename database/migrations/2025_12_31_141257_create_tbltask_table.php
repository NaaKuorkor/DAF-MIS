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
        Schema::create('tbltask', function (Blueprint $table) {
            $table->string('task_id', 10)->unique();
            $table->string('userid');
            $table->foreign('userid')->references('userid')->on('tbluser');
            $table->primary('task_id');
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->date('due_date');
            $table->string('priority')->default('Low');
            $table->string('status')->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->string('deleted')->default('0');
            $table->string('createuser')->nullable();
            $table->timestamp('createdate')->useCurrent();
            $table->string('modifyuser')->nullable();
            $table->timestamp('modifydate')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbltask');
    }
};
