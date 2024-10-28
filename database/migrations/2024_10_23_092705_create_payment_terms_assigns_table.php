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
        Schema::create('payment_terms_assigns', function (Blueprint $table) {
            $table->id();

            $table->integer('purcentage')->default(0);
            $table->integer('day_number')->default(0);
            $table->string('ref_invoice', 255);

            $table->bigInteger('id_payment_terms')->unsigned()->index();
            $table->foreign('id_payment_terms')
                    ->references('id')->on('payment_terms_proformas')
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
        Schema::dropIfExists('payment_terms_assigns');
    }
};
