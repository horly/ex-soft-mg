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
        Schema::create('creditors', function (Blueprint $table) {
            $table->id();
            $table->string('type_cr', 255);
            $table->string('reference_cr', 255);
            $table->integer('reference_number');
            $table->string('entreprise_name_cr', 255)->default('-');
            $table->string('rccm_cr', 255)->default('-');
            $table->string('id_nat_cr', 255)->default('-');
            $table->string('nif_cr', 255)->default('-');
            $table->string('account_num_cr', 255)->default('-');
            $table->string('website_cr', 255)->default('-');
            $table->string('contact_name_cr', 255);
            $table->string('fonction_contact_cr', 255);
            $table->string('phone_number_cr', 255);
            $table->string('email_adress_cr', 255);
            $table->string('address_cr', 255);
            
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
        Schema::dropIfExists('creditors');
    }
};
