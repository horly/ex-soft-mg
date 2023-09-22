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
        Schema::create('devise_gestion_ufs', function (Blueprint $table) {
            $table->id();
            $table->double('taux');
            $table->integer('id_devise')
                    ->unsigned()
                    ->index();

            $table->foreign('id_devise')
                    ->references('id')->on('devises')
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
        Schema::dropIfExists('devise_gestion_ufs');
    }
};
