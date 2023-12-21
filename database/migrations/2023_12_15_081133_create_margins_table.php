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
        Schema::create('invoice_margins', function (Blueprint $table) {
            $table->id();
            $table->string('ref_invoice', 255);
            $table->integer('margin')->default(0);
            $table->integer('invoice_saved')->default(0);

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
        Schema::dropIfExists('invoice_margins');
    }
};
