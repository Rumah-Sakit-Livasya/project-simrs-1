<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_vehicle_id')->constrained('internal_vehicles')->onDelete('cascade');
            $table->foreignId('workshop_vendor_id')->nullable()->constrained('workshop_vendors')->onDelete('set null');
            $table->foreignId('reported_by_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('inspection_result_id')->nullable()->constrained()->onDelete('set null');
            $table->text('description_of_issue');
            $table->date('service_date')->nullable();
            $table->text('work_done')->nullable();
            $table->decimal('parts_cost', 15, 2)->default(0);
            $table->decimal('labor_cost', 15, 2)->default(0);
            $table->unsignedInteger('odometer_at_service')->nullable();
            $table->string('invoice_path')->nullable();
            $table->enum('status', ['Open', 'In Progress', 'Completed'])->default('Open');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_services');
    }
};
