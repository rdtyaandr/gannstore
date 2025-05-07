<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('struk_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('struk_id')->constrained()->onDelete('cascade');
            $table->foreignId('struk_field_id')->constrained()->onDelete('cascade');
            $table->text('value');
            $table->timestamps();

            $table->unique(['struk_id', 'struk_field_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('struk_values');
    }
}; 