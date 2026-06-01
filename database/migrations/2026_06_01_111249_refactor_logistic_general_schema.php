<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Because this is a massive refactor, we will clear existing transaction data
        // to avoid foreign key chaos.
        Schema::disableForeignKeyConstraints();
        DB::table('manifest_items')->truncate();
        DB::table('manifests')->truncate();
        DB::table('shipment_status_logs')->truncate();
        DB::table('shipments')->truncate();
        DB::table('pricing_rules')->truncate();
        DB::table('routes')->truncate();

        // 1. Create Region Tables
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained('provinces')->cascadeOnDelete();
            $table->string('name');
            $table->string('type')->default('Kota'); // Kota / Kabupaten
            $table->timestamps();
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        // 2. Modify Branches
        Schema::table('branches', function (Blueprint $table) {
            // Drop old string city
            $table->dropColumn('city');
            $table->string('type')->default('drop_point')->after('name'); // hub, drop_point, gateway
            $table->foreignId('city_id')->nullable()->after('type')->constrained('cities')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->after('city_id')->constrained('districts')->nullOnDelete();
        });

        // 3. Rebuild Pricing Rules
        Schema::table('pricing_rules', function (Blueprint $table) {
            $table->dropForeign(['route_id']);
            $table->dropUnique(['route_id', 'package_category']);
            $table->dropColumn('route_id');
            $table->dropColumn('package_category');

            $table->foreignId('origin_city_id')->after('id')->constrained('cities')->cascadeOnDelete();
            $table->foreignId('destination_city_id')->after('origin_city_id')->constrained('cities')->cascadeOnDelete();
            $table->string('service_type')->default('reguler')->after('destination_city_id'); // reguler, b2b, ekonomis

            $table->unique(['origin_city_id', 'destination_city_id', 'service_type'], 'pricing_rules_unique_index');
        });

        // 4. Modify Shipments
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropForeign(['route_id']);
            $table->dropIndex(['route_id', 'status']);
            $table->dropColumn('route_id');

            $table->string('service_type')->default('reguler')->after('receiver_address');
            $table->foreignId('origin_branch_id')->nullable()->after('service_type')->constrained('branches')->nullOnDelete();
            $table->foreignId('destination_branch_id')->nullable()->after('origin_branch_id')->constrained('branches')->nullOnDelete();
            $table->foreignId('courier_id')->nullable()->after('destination_agent_id')->constrained('users')->nullOnDelete();

            $table->index(['origin_branch_id', 'status']);
            $table->index(['destination_branch_id', 'status']);
        });

        // 5. Modify Manifests
        Schema::table('manifests', function (Blueprint $table) {
            $table->dropForeign(['route_id']);
            $table->dropColumn('route_id');
            
            $table->string('type')->default('linehaul')->after('manifest_number'); // pickup, linehaul, delivery
            $table->foreignId('origin_branch_id')->nullable()->after('type')->constrained('branches')->nullOnDelete();
            $table->foreignId('destination_branch_id')->nullable()->after('origin_branch_id')->constrained('branches')->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->after('destination_branch_id')->constrained('users')->nullOnDelete();
            $table->string('vehicle_number')->nullable()->after('driver_id');
        });

        // 6. Drop Routes Table safely
        Schema::dropIfExists('routes');

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Rollback is complex due to dropping Routes table and truncating data.
        // We leave down() empty or just throw an exception since this is a one-way architectural refactor.
        throw new \Exception("Cannot rollback this massive architectural refactor automatically.");
    }
};
