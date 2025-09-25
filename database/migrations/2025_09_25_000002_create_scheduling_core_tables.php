<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_overnight')->default(false);
            $table->unsignedSmallInteger('break_minutes')->default(0);
            $table->unsignedSmallInteger('grace_minutes')->default(0);
            $table->unsignedSmallInteger('expected_hours')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('shift_rotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedTinyInteger('cycle_length_weeks')->default(2);
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('shift_rotation_weeks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rotation_id');
            $table->unsignedTinyInteger('week_index');
            $table->unsignedBigInteger('shift_id');
            $table->timestamps();

            $table->foreign('rotation_id')->references('id')->on('shift_rotations')->onDelete('cascade');
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('restrict');
            $table->unique(['rotation_id', 'week_index']);
        });

        Schema::create('shift_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('shift_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->unsignedTinyInteger('priority')->default(1); // higher wins
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('restrict');
            $table->index(['employee_id', 'start_date']);
        });

        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->boolean('is_recurring')->default(false);
            $table->unsignedBigInteger('area_id')->nullable();
            $table->unsignedBigInteger('office_id')->nullable();
            $table->timestamps();

            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('set null');
            $table->unique(['date', 'office_id', 'area_id']);
        });

        Schema::create('overtime_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('scope', ['global','area','office'])->default('global');
            $table->unsignedBigInteger('area_id')->nullable();
            $table->unsignedBigInteger('office_id')->nullable();
            $table->unsignedSmallInteger('min_minutes_threshold')->default(30);
            $table->unsignedSmallInteger('rounding_minutes')->default(15);
            $table->unsignedSmallInteger('daily_cap_minutes')->nullable();
            $table->unsignedSmallInteger('weekly_cap_minutes')->nullable();
            $table->boolean('requires_approval')->default(true);
            $table->timestamps();

            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('set null');
        });

        Schema::create('overtime_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('date');
            $table->unsignedSmallInteger('minutes');
            $table->enum('type', ['pre_shift','post_shift','holiday','weekly']);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['employee_id','date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('overtime_entries');
        Schema::dropIfExists('overtime_rules');
        Schema::dropIfExists('holidays');
        Schema::dropIfExists('shift_assignments');
        Schema::dropIfExists('shift_rotation_weeks');
        Schema::dropIfExists('shift_rotations');
        Schema::dropIfExists('shifts');
    }
};


