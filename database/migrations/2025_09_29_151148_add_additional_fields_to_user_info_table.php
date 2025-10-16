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
            // Add additional fields that might be missing
            if (!Schema::hasColumn('user_infos', 'gmail')) {
                $table->string('gmail')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('user_infos', 'cnic')) {
                $table->string('cnic')->nullable()->after('gmail');
            }
            if (!Schema::hasColumn('user_infos', 'passport')) {
                $table->string('passport')->nullable()->after('cnic');
            }
            if (!Schema::hasColumn('user_infos', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('passport');
            }
            if (!Schema::hasColumn('user_infos', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('user_infos', 'address')) {
                $table->text('address')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('user_infos', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('user_infos', 'state')) {
                $table->string('state')->nullable()->after('city');
            }
            if (!Schema::hasColumn('user_infos', 'country')) {
                $table->string('country')->nullable()->after('state');
            }
            if (!Schema::hasColumn('user_infos', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('country');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_infos', function (Blueprint $table) {
            $table->dropColumn([
                'gmail', 'cnic', 'passport', 'date_of_birth', 'gender', 
                'address', 'city', 'state', 'country', 'postal_code'
            ]);
        });
    }
};
