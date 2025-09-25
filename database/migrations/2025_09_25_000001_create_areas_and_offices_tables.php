<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('areas')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->string('name');
            $table->string('code')->nullable();
            $table->timestamps();

            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
        });

        // Alter devices to reference offices
        Schema::table('devices', function (Blueprint $table) {
            $table->unsignedBigInteger('office_id')->nullable()->after('lokasi');
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropForeign(['office_id']);
            $table->dropColumn('office_id');
        });

        Schema::dropIfExists('offices');
        Schema::dropIfExists('areas');
    }
};


