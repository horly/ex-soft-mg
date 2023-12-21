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
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('reference_sales_invoice', 255);
            $table->integer('reference_number');
            $table->double('sub_total');
            $table->double('total');
            $table->double('vat_amount');
            $table->double('amount_received');

            $table->bigInteger('id_client')->unsigned()->index();
            $table->foreign('id_client')
                    ->references('id')->on('clients')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->bigInteger('id_user')->unsigned()->index();
            $table->foreign('id_user')
                    ->references('id')->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->bigInteger('id_fu')->unsigned()->index();
            $table->foreign('id_fu')
                    ->references('id')->on('functional_units')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            
            $table->timestamp('due_date'); //échéance
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_invoices');
    }
};
