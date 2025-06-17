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
        Schema::create('visit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_id');
            $table->date('visit_date');
            $table->dateTime('visit_time');
            $table->dateTime('leave_time');
            $table->string('purpose');
            $table->string('appointer');
            $table->string('dept');
            $table->string('security_id');
            $table->string('visitor_card_id');
            $table->string('void')->default('false');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_logs');
    }
};
