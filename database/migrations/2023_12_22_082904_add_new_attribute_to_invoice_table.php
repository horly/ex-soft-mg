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
        Schema::table('sales_invoices', function (Blueprint $table) {
            //
            $table->integer('discount_choice')->default(0)->after('reference_number');
            $table->string('discount_type')->nullable()->after('discount_choice');
            $table->double('discount_value')->after('discount_type');
            $table->integer('vat')->default(0);
            $table->double('discount_apply_amount')->after('vat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            //
        });
    }
};
