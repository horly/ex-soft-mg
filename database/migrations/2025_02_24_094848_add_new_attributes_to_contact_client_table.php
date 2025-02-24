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
        Schema::table('customer_contacts', function (Blueprint $table) {
            //
            $table->string('fonction_contact_cl')->nullable()->change();
            $table->string('departement_cl')->nullable()->change();
            $table->string('email_adress_cl')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_contacts', function (Blueprint $table) {
            //
        });
    }
};
