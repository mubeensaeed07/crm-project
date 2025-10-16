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
            $table->boolean('can_view_reports')->default(false)->after('module_id');
            $table->boolean('can_create_users')->default(false)->after('can_view_reports');
            $table->boolean('can_edit_users')->default(false)->after('can_create_users');
            $table->boolean('can_delete_users')->default(false)->after('can_edit_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_modules', function (Blueprint $table) {
            $table->dropColumn([
                'can_view_reports',
                'can_create_users', 
                'can_edit_users',
                'can_delete_users'
            ]);
        });
    }
};