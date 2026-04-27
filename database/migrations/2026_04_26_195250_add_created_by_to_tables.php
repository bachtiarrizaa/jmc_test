<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tables = [
            'users',
            'employees',
            'transport_allowances',
            'transport_settings',
            'departments',
            'positions',
            'employee_types'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                if (!Schema::hasColumn($table, 'created_by')) {
                    Schema::table($table, function (Blueprint $table) {
                        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                    });
                }
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'users',
            'employees',
            'transport_allowances',
            'transport_settings',
            'departments',
            'positions',
            'employee_types'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'created_by')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign(['created_by']);
                    $table->dropColumn('created_by');
                });
            }
        }
    }
};
