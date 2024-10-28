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
        Schema::create('debtors', function (Blueprint $table) {
            $table->id();
            $table->string('type_deb', 255);
            $table->string('reference_deb', 255);
            $table->integer('reference_number');
            $table->string('entreprise_name_deb', 255)->default('-')->nullable();;
            $table->string('rccm_deb', 255)->default('-')->nullable();
            $table->string('id_nat_deb', 255)->default('-')->nullable();
            $table->string('nif_deb', 255)->default('-')->nullable();
            $table->string('account_num_deb', 255)->default('-')->nullable();
            $table->string('website_deb', 255)->default('-')->nullable();
            $table->string('contact_name_deb', 255);
            $table->string('fonction_contact_deb', 255);
            $table->string('phone_number_deb', 255);
            $table->string('email_adress_deb', 255);
            $table->string('address_deb', 255);

            $table->bigInteger('id_user')->unsigned()->index();
            $table->foreign('id_user')
                    ->references('id')->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->bigInteger('id_fu')->unsigned()->index();
            $table->foreign('id_fu')
                    ->references('id')->on('functional_units')
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
        Schema::dropIfExists('debtors');
    }
};
