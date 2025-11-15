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
        Schema::create('bus_routes', function (Blueprint $table) {
            $table->id();
            $table->string('ma_tuyen');
            $table->string('diem_di');
            $table->string('diem_den');
            $table->date('ngay')->nullable();
            $table->time('thoi_gian_bat_dau')->nullable();
            $table->time('thoi_gian_ket_thuc')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('coords')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_routes');
    }
};
