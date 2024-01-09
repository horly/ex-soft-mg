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
        Schema::table('clients', function (Blueprint $table) {
            //
            $table->dropColumn('contact_name_cl');
            $table->dropColumn('fonction_contact_cl');
            $table->dropColumn('phone_number_cl');
            $table->dropColumn('email_adress_cl');
            $table->dropColumn('address_cl');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            //
        });
    }
};
