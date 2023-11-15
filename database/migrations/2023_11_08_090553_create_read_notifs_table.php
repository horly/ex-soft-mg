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
        Schema::create('read_notifs', function (Blueprint $table) {
            $table->id();

            $table->integer('read')->default(0);

            $table->bigInteger('id_notif')->unsigned()->index();
            $table->foreign('id_notif')
                    ->references('id')->on('notifications')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            
            $table->bigInteger('id_user')->unsigned()->index();
            $table->foreign('id_user')
                    ->references('id')->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            
            $table->bigInteger('id_sender')->unsigned()->index();
            $table->foreign('id_sender')
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
        Schema::dropIfExists('read_notifs');
    }
};
