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
        //  Attendance Raw Logs for storing raw punch data from devices
        Schema::create('attendance_raw_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('staff_id')->index();
            $table->string('employee_code', 50)->nullable()->index();
            $table->dateTime('log_time')->index();
            $table->enum('direction', [
                'PUNCH_IN',
                'PUNCH_OUT',
                'BREAK_IN',
                'BREAK_OUT'
            ])->index();
            $table->string('device_sn', 100)->nullable();
            $table->string('device_name', 150)->nullable();
            $table->string('verification_type', 50)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('gps', 100)->nullable();
            $table->longText('raw_payload')->nullable();
            $table->timestamps();

            $table->index(['staff_id', 'log_time']);
        });

        // Daily Attendance Summary for storing daily attendance data
        Schema::create('attendance_daily', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('staff_id')->index();
            $table->string('employee_code', 50)->nullable()->index();
            $table->date('attendance_date')->index();
            $table->dateTime('punch_in_time')->nullable();
            $table->dateTime('punch_out_time')->nullable();
            $table->integer('total_work_minutes')->default(0);
            $table->integer('total_break_minutes')->default(0);
            $table->integer('late_minutes')->default(0);
            $table->integer('overtime_minutes')->default(0);
            $table->enum('attendance_status', [
                'PRESENT',
                'LATE',
                'ABSENT',
                'HALF_DAY',
                'LEAVE',
                'HOLIDAY',
                'WEEK_OFF'
            ])->default('PRESENT');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['staff_id', 'attendance_date']);
        });

        // Attendance Breaks for storing break details linked to daily attendance
        Schema::create('attendance_breaks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id')->index();
            $table->unsignedBigInteger('attendance_daily_id')->index();
            $table->dateTime('break_in_time');
            $table->dateTime('break_out_time')->nullable();
            $table->integer('break_minutes')->default(0);
            $table->enum('break_type', [
                'LUNCH',
                'TEA',
                'PERSONAL',
                'OTHER'
            ])->default('OTHER');
            $table->timestamps();

            $table->index(['staff_id', 'attendance_daily_id']);
        });

        // Attendance Audit for storing audit trails of attendance changes
        Schema::create('attendance_audit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id')->index();
            $table->unsignedBigInteger('attendance_daily_id')->nullable()->index();
            $table->string('action_type', 50); // MANUAL_EDIT, AUTO_CORRECTION, ADMIN_OVERRIDE
            $table->dateTime('old_punch_in_time')->nullable();
            $table->dateTime('new_punch_in_time')->nullable();
            $table->dateTime('old_punch_out_time')->nullable();
            $table->dateTime('new_punch_out_time')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // Travel Reports for storing travel data linked to attendance
        Schema::create('travel_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id')->index();
            $table->date('travel_date')->index();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('location_name', 255)->nullable();
            $table->string('gps_status', 100)->nullable();
            $table->text('device_information')->nullable();
            $table->timestamps();

            $table->index(['staff_id', 'travel_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_reports');
        Schema::dropIfExists('attendance_audit');
        Schema::dropIfExists('attendance_breaks');
        Schema::dropIfExists('attendance_daily');
        Schema::dropIfExists('attendance_raw_logs');
    }
};
