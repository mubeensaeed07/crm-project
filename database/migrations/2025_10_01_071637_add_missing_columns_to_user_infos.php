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
        Schema::table('user_infos', function (Blueprint $table) {
            if (!Schema::hasColumn('user_infos', 'superadmin_id')) {
                $table->bigInteger('superadmin_id')->nullable();
            }
            if (!Schema::hasColumn('user_infos', 'admin_id')) {
                $table->bigInteger('admin_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_infos', function (Blueprint $table) {
            $table->dropColumn(['superadmin_id', 'admin_id']);
        });
    }
};