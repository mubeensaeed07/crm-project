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
        Schema::table('user_modules', function (Blueprint $table) {
            // Add missing permission columns
            if (!Schema::hasColumn('user_modules', 'can_reset_passwords')) {
                $table->boolean('can_reset_passwords')->default(false)->after('can_delete_users');
            }
            if (!Schema::hasColumn('user_modules', 'can_assign_modules')) {
                $table->boolean('can_assign_modules')->default(false)->after('can_reset_passwords');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_modules', function (Blueprint $table) {
            $table->dropColumn([
                'can_reset_passwords',
                'can_assign_modules'
            ]);
        });
    }
};