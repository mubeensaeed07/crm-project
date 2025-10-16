<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add created_by_type and created_by_id to user_infos table
        Schema::table('user_infos', function (Blueprint $table) {
            if (!Schema::hasColumn('user_infos', 'created_by_type')) {
                $table->string('created_by_type')->nullable();
            }
            if (!Schema::hasColumn('user_infos', 'created_by_id')) {
                $table->bigInteger('created_by_id')->nullable();
            }
        });

        // Copy data from users table to user_infos table
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $userInfo = DB::table('user_infos')->where('user_id', $user->id)->first();
            if ($userInfo) {
                DB::table('user_infos')
                    ->where('user_id', $user->id)
                    ->update([
                        'created_by_type' => $user->created_by_type,
                        'created_by_id' => $user->created_by_id
                    ]);
            }
        }

        // Remove created_by_type and created_by_id from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['created_by_type', 'created_by_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add created_by_type and created_by_id back to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('created_by_type')->nullable();
            $table->bigInteger('created_by_id')->nullable();
        });

        // Copy data back from user_infos to users table
        $userInfos = DB::table('user_infos')->get();
        foreach ($userInfos as $userInfo) {
            DB::table('users')
                ->where('id', $userInfo->user_id)
                ->update([
                    'created_by_type' => $userInfo->created_by_type,
                    'created_by_id' => $userInfo->created_by_id
                ]);
        }

        // Remove created_by_type and created_by_id from user_infos table
        Schema::table('user_infos', function (Blueprint $table) {
            $table->dropColumn(['created_by_type', 'created_by_id']);
        });
    }
};