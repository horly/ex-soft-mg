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
        Schema::create('functional_units', function (Blueprint $table) {
            $table->id();
            $table->string("name", 255);
            $table->string("address");
            $table->bigInteger('id_entreprise')->unsigned()->index();
            $table->foreign('id_entreprise')
                    ->references('id')->on('entreprises')
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
        Schema::dropIfExists('functional_units');
    }
};
