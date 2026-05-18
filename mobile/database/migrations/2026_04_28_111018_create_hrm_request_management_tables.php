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
        // Request Types
        Schema::create('hrm_request_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // LEAVE, WFH, REG, OD
            $table->string('name', 100);
            $table->string('icon', 100)->nullable();
            $table->string('color_code', 20)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Employee Requests
        Schema::create('hrm_employee_requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('request_type_id');

            $table->date('request_date');

            // Important for calendar and report view
            $table->date('from_date');
            $table->date('to_date');

            // Useful for short leave / on duty / WFH timing
            $table->time('from_time')->nullable();
            $table->time('to_time')->nullable();

            // Pre-calculated values for fast reports
            $table->decimal('total_days', 8, 2)->default(0);
            $table->decimal('total_hours', 8, 2)->default(0);

            // For expense requests
            $table->decimal('amount', 12, 2)->default(0);

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'cancelled'
            ])->default('pending');

            $table->text('reason')->nullable();

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_remarks')->nullable();

            $table->string('attachment', 255)->nullable();

            $table->timestamps();
            $table->foreign('request_type_id')
                ->references('id')
                ->on('hrm_request_types')
                ->onDelete('cascade');
            $table->index(
                ['staff_id', 'from_date', 'to_date', 'status'],
                'idx_emp_calendar'
            );
            $table->index(
                ['staff_id', 'request_type_id', 'status', 'created_at'],
                'idx_emp_report'
            );
            $table->index(
                ['status', 'approved_by'],
                'idx_approval'
            );
        });

        // Request Details
        Schema::create('hrm_request_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('request_id');

            // Store extra request-specific data
            $table->json('request_data')->nullable();

            $table->timestamps();

            $table->foreign('request_id')
                ->references('id')
                ->on('hrm_employee_requests')
                ->onDelete('cascade');

            $table->index('request_id');
        });

        // Request Approvals
        Schema::create('hrm_request_approvals', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('action_by');

            $table->enum('action_type', [
                'submitted',
                'approved',
                'rejected',
                'cancelled'
            ]);

            $table->text('remarks')->nullable();
            $table->timestamp('action_at')->useCurrent();

            $table->timestamps();

            $table->foreign('request_id')
                ->references('id')
                ->on('hrm_employee_requests')
                ->onDelete('cascade');

            $table->index(['request_id', 'action_type']);
        });

    }
    

    public function down(): void
    {
        Schema::dropIfExists('hrm_request_approvals');
        Schema::dropIfExists('hrm_request_details');
        Schema::dropIfExists('hrm_employee_requests');
        Schema::dropIfExists('hrm_request_types');
    }
};