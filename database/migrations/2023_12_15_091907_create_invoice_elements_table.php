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
        Schema::create('invoice_elements', function (Blueprint $table) {
            $table->id();
            $table->string('ref_invoice', 255);
            $table->string('ref_article', 255)->nullable();
            $table->string('ref_service', 255)->nullable();
            $table->string('description_inv_elmnt', 255);
            $table->integer('quantity')->default(1);
            $table->double('unit_price_inv_elmnt');
            $table->double('total_price_inv_elmnt');
            $table->integer('is_an_article')->default(1);

            $table->bigInteger('id_marge')->unsigned()->index();
            $table->foreign('id_marge')
                    ->references('id')->on('invoice_margins')
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

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_elements');
    }
};
