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
        Schema::create('note_documents', function (Blueprint $table) {
            $table->id();
            $table->string('type_doc', 255);
            $table->string('type_note', 255);
            $table->string('note_content', 255);
            $table->string('reference_doc', 255);
            $table->integer('bold_note')->default(0)->nullable();
            $table->integer('italic_note')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_documents');
    }
};
