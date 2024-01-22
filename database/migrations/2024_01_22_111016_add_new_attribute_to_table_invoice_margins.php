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
        Schema::table('invoice_margins', function (Blueprint $table) {
            //
            $table->integer('is_simple_invoice_inv')->after('is_delivery_note_marge')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_margins', function (Blueprint $table) {
            //
        });
    }
};
