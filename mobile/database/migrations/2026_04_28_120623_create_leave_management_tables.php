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
        // Leave Types Master
        Schema::create('hrm_leave_types', function (Blueprint $table) {
            $table->id();

            $table->string('code', 30)->unique(); 
            // casual, sick, maternity, compensatory, lop, admin

            $table->string('name', 100);

            $table->integer('yearly_limit')->default(0);

            $table->boolean('is_paid')->default(true);

            $table->boolean('carry_forward')->default(false);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        // Employee Leave Balances
        Schema::create('hrm_employee_leave_balances', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('staff_id');

            $table->unsignedBigInteger('leave_type_id');

            $table->year('leave_year');

            $table->decimal('allocated', 8, 2)->default(0);

            $table->decimal('used', 8, 2)->default(0);

            $table->decimal('remaining', 8, 2)->default(0);

            $table->timestamps();

            $table->foreign('leave_type_id')
                ->references('id')
                ->on('hrm_leave_types')
                ->onDelete('cascade');

            $table->unique(
                ['staff_id', 'leave_type_id', 'leave_year'],
                'uq_employee_leave_balance'
            );

            $table->index(
                ['staff_id', 'leave_year'],
                'idx_employee_leave_year'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrm_employee_leave_balances');
        Schema::dropIfExists('hrm_leave_types');
    }
};