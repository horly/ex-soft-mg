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
        Schema::create('customer_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('fullname_cl', 255);
            $table->string('fonction_contact_cl', 255);
            $table->string('phone_number_cl', 255);
            $table->string('email_adress_cl', 255);
            $table->string('address_cl', 255);
            $table->string('departement_cl', 255);
            $table->timestamps();

            $table->bigInteger('id_client')->unsigned()->index();
            $table->foreign('id_client')
                    ->references('id')->on('clients')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_contacts');
    }
};
