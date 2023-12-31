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
            $table->string('role', 255)
                ->after('remember_token')
                ->default('user');

            $table->string('function', 255)
                ->after('role');

            $table->text('address')
                ->after('function')
                ->nullable();

            $table->string('phone_number', 255)
                ->after('address')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
