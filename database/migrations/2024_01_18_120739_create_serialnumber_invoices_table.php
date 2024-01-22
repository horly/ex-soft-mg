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
        Schema::create('serial_number_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('description', 255);
            $table->bigInteger('id_invoice_element')->unsigned()->index();
            $table->foreign('id_invoice_element')
                    ->references('id')->on('invoice_elements')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serialnumber_invoices');
    }
};
