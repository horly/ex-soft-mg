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
        Schema::create('functional_unit_phones', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 255);
            $table->bigInteger('id_func_unit')->unsigned()->index();
            $table->foreign('id_func_unit')
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
        Schema::dropIfExists('functional_unit_phones');
    }
};
