<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mood_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('entry_date');
            $table->string('mood');
            $table->unsignedTinyInteger('intensity');
            $table->json('emotions')->nullable();
            $table->text('notes')->nullable();
            $table->string('weather_summary')->nullable();
            $table->decimal('temperature', 5, 1)->nullable();
            $table->json('weather_data')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'entry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mood_entries');
    }
};
