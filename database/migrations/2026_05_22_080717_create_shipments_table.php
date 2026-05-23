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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->string('sender_name');
            $table->string('sender_phone');
            $table->string('sender_city');
            $table->text('sender_address')->nullable();
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->string('receiver_city');
            $table->text('receiver_address')->nullable();
            $table->foreignId('route_id')->constrained('routes')->restrictOnDelete();
            $table->string('item_description');
            $table->string('package_category');
            $table->unsignedInteger('koli_count')->default(1);
            $table->decimal('actual_weight', 8, 2)->default(0);
            $table->decimal('length_cm', 8, 2)->nullable();
            $table->decimal('width_cm', 8, 2)->nullable();
            $table->decimal('height_cm', 8, 2)->nullable();
            $table->decimal('volume_weight', 8, 2)->default(0);
            $table->decimal('chargeable_weight', 8, 2)->default(0);
            $table->decimal('base_price', 12, 2)->default(0);
            $table->decimal('pickup_fee', 12, 2)->default(0);
            $table->decimal('delivery_fee', 12, 2)->default(0);
            $table->decimal('packing_fee', 12, 2)->default(0);
            $table->decimal('handling_fee', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_shipping_cost', 12, 2)->default(0);
            $table->string('payment_status')->default('unpaid')->index();
            $table->string('payment_method')->nullable();
            $table->string('status')->default('checked_in')->index();
            $table->dateTime('estimated_departure_at')->nullable();
            $table->dateTime('estimated_arrival_at')->nullable();
            $table->dateTime('checked_in_at')->nullable()->index();
            $table->dateTime('completed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('destination_agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('public_tracking_token')->unique();
            $table->text('internal_note')->nullable();
            $table->text('customer_note')->nullable();
            $table->timestamps();

            $table->index(['receipt_number', 'sender_name', 'receiver_name']);
            $table->index(['route_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
