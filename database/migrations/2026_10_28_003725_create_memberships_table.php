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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('plan_id')
                ->constrained('plans')
                ->restrictOnDelete();

            $table->foreignId('sede_id')
                ->constrained('sedes')
                ->restrictOnDelete();

            $table->decimal('price_at_signup', 10, 2);
            $table->string('status')->default('pending');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
