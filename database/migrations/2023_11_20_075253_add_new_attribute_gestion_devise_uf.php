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
        Schema::table('devise_gestion_ufs', function (Blueprint $table) {
            //
            $table->integer('default_cur_manage')->after('taux')->default('0');

            $table->bigInteger('id_fu')
                    ->unsigned()
                    ->index();
            $table->foreign('id_fu')
                    ->references('id')->on('functional_units')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devise_gestion_ufs', function (Blueprint $table) {
            //
        });
    }
};
