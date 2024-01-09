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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('type', 255);
            $table->string('entreprise_name_cl', 255)->nullable();
            $table->string('rccm_cl', 255)->nullable();
            $table->string('id_nat_cl', 255)->nullable();
            $table->string('account_num_cl', 255)->nullable();
            $table->string('website_cl', 255)->nullable();
            $table->string('contact_name_cl', 255);
            $table->string('fonction_contact_cl', 255);
            $table->string('phone_number_cl', 255);
            $table->string('email_adress_cl', 255);
            $table->string('address_cl', 255);
            
            $table->bigInteger('id_user')->unsigned()->index();
            $table->foreign('id_user')
                    ->references('id')->on('users')
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
        Schema::dropIfExists('clients');
    }
};
