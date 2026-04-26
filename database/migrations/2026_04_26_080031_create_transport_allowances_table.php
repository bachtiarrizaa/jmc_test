<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transport_allowances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->restrictOnDelete();
            $table->foreignId('setting_id')->constrained('transport_settings')->restrictOnDelete();
            $table->decimal('km', 8, 2);
            $table->integer('working_days');
            $table->decimal('amount', 12, 2);
            $table->tinyInteger('month');
            $table->year('year');
            $table->timestamps();

            $table->unique(['employee_id', 'month', 'year'], 'transport_allowances_employee_period_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_allowances');
    }
};
