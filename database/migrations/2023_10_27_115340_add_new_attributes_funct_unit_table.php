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
        Schema::table('functional_units', function (Blueprint $table) {
            //
            $table->bigInteger('sub_id')->unsigned()->index()->after('id_entreprise');
            $table->foreign('sub_id')
                    ->references('id')->on('subscriptions')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('functional_units', function (Blueprint $table) {
            //
        });
    }
};
