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
        // Add salary to user_infos table
        Schema::table('user_infos', function (Blueprint $table) {
            if (!Schema::hasColumn('user_infos', 'salary')) {
                $table->decimal('salary', 10, 2)->nullable();
            }
        });

        // Copy salary data from users table to user_infos table
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $userInfo = DB::table('user_infos')->where('user_id', $user->id)->first();
            if ($userInfo) {
                DB::table('user_infos')
                    ->where('user_id', $user->id)
                    ->update([
                        'salary' => $user->salary
                    ]);
            }
        }

        // Remove salary from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add salary back to users table
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('salary', 10, 2)->nullable();
        });

        // Copy salary data back from user_infos to users table
        $userInfos = DB::table('user_infos')->get();
        foreach ($userInfos as $userInfo) {
            DB::table('users')
                ->where('id', $userInfo->user_id)
                ->update([
                    'salary' => $userInfo->salary
                ]);
        }

        // Remove salary from user_infos table
        Schema::table('user_infos', function (Blueprint $table) {
            $table->dropColumn('salary');
        });
    }
};