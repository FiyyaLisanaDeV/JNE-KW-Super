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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('origin_city');
            $table->string('destination_city');
            $table->string('origin_code', 10);
            $table->string('destination_code', 10);
            $table->string('route_code', 25)->unique();
            $table->unsignedSmallInteger('estimated_duration_hours')->default(24);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->index(['origin_city', 'destination_city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
