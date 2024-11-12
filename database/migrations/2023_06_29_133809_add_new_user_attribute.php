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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('role'); //on supprime la colonne role
            $table->bigInteger('role_id')->unsigned()->index();
            $table->foreign('role_id')
                ->references('id')->on('roles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            /*
            $table->bigInteger('grade_id')->unsigned()->index();
            $table->foreign('grade_id')
                ->references('id')->on('grades')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
