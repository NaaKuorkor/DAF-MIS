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
        Schema::create('tblpayment', function (Blueprint $table) {
            $table->string('country_code', 50);
            $table->string('branch_code', 50);
            $table->string('mid', 20);
            $table->string('mode', 50);
            $table->string('checqueno', 50);
            $table->string('bankname', 50);
            $table->string('branch', 50);
            $table->char('paymenttype', 3);
            $table->string('month', 10);
            $table->char('year', 4);
            $table->char('currency', 3);
            $table->decimal('amount', 12, 0);
            $table->string('details', 50);
            $table->date('transdate');
            $table->char('status', 1);
            $table->char('deleted', 1);
            $table->string('createuser', 50);
            $table->date('createdate')->useCurrent();
            $table->string('modifyuser', 50);
            $table->date('modifydate')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblpayment');
    }
};
