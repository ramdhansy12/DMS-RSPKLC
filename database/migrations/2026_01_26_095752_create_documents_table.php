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
        // Schema::create('documents', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
        Schema::create('documents', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('document_number')->unique();
        $table->enum('category', ['SPO','MOU','KEBIJAKAN','PEDOMAN','LAINNYA']);
        $table->string('unit');
        $table->string('status')->default('aktif');
        $table->string('current_version');
        $table->foreignId('created_by')->constrained('users');
        $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
