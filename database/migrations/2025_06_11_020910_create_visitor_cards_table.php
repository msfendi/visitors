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
        Schema::create('visitor_cards', function (Blueprint $table) {
            $table->id();
            $table->string('rfid')->nullable();
            $table->string('visitor_code')->nullable();
            $table->string('visitor_number')->nullable();
            $table->string('status_card')->default('available');
            $table->string('qr_name')->nullable();
            $table->string('qr_path')->nullable();
            $table->string('void')->default('false');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_cards');
    }
};
