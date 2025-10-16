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
        Schema::table('supervisor_permissions', function (Blueprint $table) {
            // Add Finance-specific permissions
            $table->boolean('can_mark_salary_paid')->default(false)->after('can_view_reports');
            $table->boolean('can_mark_salary_pending')->default(false)->after('can_mark_salary_paid');
            $table->boolean('can_view_salary_data')->default(false)->after('can_mark_salary_pending');
            $table->boolean('can_manage_salary_payments')->default(false)->after('can_view_salary_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supervisor_permissions', function (Blueprint $table) {
            $table->dropColumn([
                'can_mark_salary_paid',
                'can_mark_salary_pending', 
                'can_view_salary_data',
                'can_manage_salary_payments'
            ]);
        });
    }
};