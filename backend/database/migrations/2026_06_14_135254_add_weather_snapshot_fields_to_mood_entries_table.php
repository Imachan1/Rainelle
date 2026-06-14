<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mood_entries', function (Blueprint $table) {
            $table->decimal('temperature', 5, 2)->nullable()->after('note');
            $table->unsignedTinyInteger('humidity')->nullable()->after('temperature');
            $table->unsignedSmallInteger('pressure')->nullable()->after('humidity');
            $table->decimal('wind_speed', 5, 2)->nullable()->after('pressure');
            $table->string('weather_condition', 80)->nullable()->after('wind_speed');
        });
    }

    public function down(): void
    {
        Schema::table('mood_entries', function (Blueprint $table) {
            $table->dropColumn([
                'temperature',
                'humidity',
                'pressure',
                'wind_speed',
                'weather_condition',
            ]);
        });
    }
};
