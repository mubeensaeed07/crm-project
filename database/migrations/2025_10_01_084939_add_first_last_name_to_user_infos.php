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
        Schema::table('user_infos', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('user_id');
            $table->string('last_name')->nullable()->after('first_name');
        });

        // Copy first_name and last_name from users table to user_infos
        $userInfos = DB::table('user_infos')->get();
        foreach ($userInfos as $userInfo) {
            $user = DB::table('users')->where('id', $userInfo->user_id)->first();
            if ($user) {
                DB::table('user_infos')
                    ->where('id', $userInfo->id)
                    ->update([
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_infos', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
        });
    }
};