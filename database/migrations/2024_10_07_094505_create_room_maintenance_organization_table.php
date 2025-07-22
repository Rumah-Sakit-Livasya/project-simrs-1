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
        Schema::create('room_maintenance_organization', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('room_maintenance_id');
            $table->unsignedBiginteger('organization_id');
            $table->timestamps();


            $table->foreign('room_maintenance_id')->references('id')
                ->on('room_maintenance')->onDelete('cascade');
            $table->foreign('organization_id')->references('id')
                ->on('organizations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_maintenance_organization');
    }
};
