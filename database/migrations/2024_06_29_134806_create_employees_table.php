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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('job_position_id')->nullable();
            $table->unsignedBigInteger('job_level_id')->nullable();
            $table->unsignedBigInteger('approval_line')->nullable();
            $table->unsignedBigInteger('approval_line_parent')->nullable();
            $table->string('employee_code')->nullable();
            $table->string('title')->nullable();
            $table->string('fullname')->nullable();
            $table->string('degree')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('birthdate')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('religion')->nullable();
            $table->string('last_education')->nullable();
            $table->string('identity_type')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('identity_expire_date')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('citizen_id_address')->nullable();
            $table->text('residental_address')->nullable();
            $table->string('barcode')->nullable();
            $table->string('employment_status')->nullable();
            $table->string('join_date')->nullable();
            $table->string('end_status_date')->nullable();
            $table->string('resign_date')->nullable();
            $table->string('basic_salary')->nullable();
            $table->string('salary_type')->nullable();
            $table->string('payment_schedule')->nullable();
            $table->string('protate_setting')->nullable();
            $table->boolean('allowed_for_overtime')->nullable();
            $table->string('npwp')->nullable();
            $table->string('ptkp_status')->nullable();
            $table->string('tax_methode')->nullable();
            $table->string('tax_salary')->nullable();
            $table->string('taxable_date')->nullable();
            $table->string('employment_tax_status')->nullable();
            $table->string('beginning_netto')->nullable();
            $table->string('pph21_paid')->nullable();
            $table->string('bpjs_ker_number')->nullable();
            $table->string('npp_ker_bpjs')->nullable();
            $table->string('bpjs_ker_date')->nullable();
            $table->string('bpjs_kes_number')->nullable();
            $table->string('bpjs_kes_family')->nullable();
            $table->string('bpjs_kes_date')->nullable();
            $table->string('bpjs_kes_cost')->nullable();
            $table->string('jht_cost')->nullable();
            $table->string('jaminan_pensiun_cost')->nullable();
            $table->string('jaminan_pensiun_date')->nullable();
            $table->string('sip')->nullable();
            $table->string('expire_sip')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('ttd')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('set null');
            $table->foreign('job_position_id')->references('id')->on('job_positions')->onDelete('set null');
            $table->foreign('job_level_id')->references('id')->on('job_levels')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
