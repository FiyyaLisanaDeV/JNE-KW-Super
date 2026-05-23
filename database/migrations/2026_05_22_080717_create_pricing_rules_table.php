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
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('routes')->cascadeOnDelete();
            $table->string('package_category');
            $table->decimal('base_price', 12, 2)->default(0);
            $table->decimal('price_per_kg', 12, 2)->default(0);
            $table->decimal('minimum_weight', 8, 2)->default(1);
            $table->unsignedInteger('volume_divisor')->default(6000);
            $table->decimal('pickup_fee', 12, 2)->default(0);
            $table->decimal('delivery_fee', 12, 2)->default(0);
            $table->decimal('packing_fee', 12, 2)->default(0);
            $table->decimal('handling_fee', 12, 2)->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['route_id', 'package_category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};
