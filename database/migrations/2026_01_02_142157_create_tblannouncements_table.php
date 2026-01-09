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
            $table->string('announcement_id', 20)->primary();
            $table->string('title', 255);
            $table->text('content');
            $table->enum('status', ['draft', 'scheduled', 'active', 'expired'])->default('draft');
            $table->enum('priority', ['info', 'alert', 'urgent'])->default('info');
            $table->json('audience'); // Store array of audience types
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('read_count')->default(0);
            $table->string('created_by'); // userid of creator
            $table->string('deleted')->default('0');
            $table->string('createuser')->nullable();
            $table->timestamp('createdate')->useCurrent();
            $table->string('modifyuser')->nullable();
            $table->timestamp('modifydate')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('status');
            $table->index('created_by');
            $table->index('scheduled_at');
        });

        // Create announcement reads tracking table
        Schema::create('tblannouncement_reads', function (Blueprint $table) {
            $table->id();
            $table->string('announcement_id', 20);
            $table->string('userid');
            $table->timestamp('read_at')->useCurrent();
            
            $table->foreign('announcement_id')->references('announcement_id')->on('tblannouncements')->onDelete('cascade');
            $table->unique(['announcement_id', 'userid']);
            $table->index('userid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblannouncement_reads');
        Schema::dropIfExists('tblannouncements');
    }
};