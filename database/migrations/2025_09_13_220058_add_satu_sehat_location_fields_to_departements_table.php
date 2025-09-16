<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departements', function (Blueprint $table) {
            // Kolom untuk menyimpan ID Location dari Satu Sehat
            $table->uuid('satu_sehat_location_id')->nullable()->after('satu_sehat_organization_id');

            // Kolom untuk menyimpan detail lain dari resource Location
            $table->string('location_mode')->nullable()->default('instance')->after('satu_sehat_location_id');
            $table->string('location_physical_type')->nullable()->default('ro')->after('location_mode'); // ro = room
            $table->enum('location_status', ['active', 'inactive', 'suspended'])->default('inactive')->after('location_physical_type');
        });
    }

    public function down(): void
    {
        Schema::table('departements', function (Blueprint $table) {
            $table->dropColumn([
                'satu_sehat_location_id',
                'location_mode',
                'location_physical_type',
                'location_status'
            ]);
        });
    }
};
