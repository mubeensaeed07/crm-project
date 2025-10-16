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
            // Add missing columns that don't exist yet
            if (!Schema::hasColumn('user_infos', 'job_title')) {
                $table->string('job_title')->nullable();
            }
            if (!Schema::hasColumn('user_infos', 'department')) {
                $table->string('department')->nullable();
            }
            if (!Schema::hasColumn('user_infos', 'company')) {
                $table->string('company')->nullable();
            }
            if (!Schema::hasColumn('user_infos', 'bio')) {
                $table->text('bio')->nullable();
            }
            if (!Schema::hasColumn('user_infos', 'linkedin_url')) {
                $table->string('linkedin_url')->nullable();
            }
            if (!Schema::hasColumn('user_infos', 'twitter_url')) {
                $table->string('twitter_url')->nullable();
            }
            if (!Schema::hasColumn('user_infos', 'website_url')) {
                $table->string('website_url')->nullable();
            }
            if (!Schema::hasColumn('user_infos', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable();
            }
            if (!Schema::hasColumn('user_infos', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable();
            }
            if (!Schema::hasColumn('user_infos', 'emergency_contact_relationship')) {
                $table->string('emergency_contact_relationship')->nullable();
            }
            if (!Schema::hasColumn('user_infos', 'timezone')) {
                $table->string('timezone')->default('UTC');
            }
            if (!Schema::hasColumn('user_infos', 'language')) {
                $table->string('language')->default('en');
            }
            if (!Schema::hasColumn('user_infos', 'email_notifications')) {
                $table->boolean('email_notifications')->default(true);
            }
            if (!Schema::hasColumn('user_infos', 'sms_notifications')) {
                $table->boolean('sms_notifications')->default(false);
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
                'job_title', 'department', 'company', 'bio',
                'linkedin_url', 'twitter_url', 'website_url',
                'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship',
                'timezone', 'language', 'email_notifications', 'sms_notifications'
            ]);
        });
    }
};