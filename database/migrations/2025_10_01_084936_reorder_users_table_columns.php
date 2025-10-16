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
        // We can't easily reorder columns in MySQL, so we'll create a new table with the correct order
        // and copy the data over
        
        // Create new users table with correct column order
        Schema::create('users_new', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->bigInteger('admin_id')->nullable();
            $table->bigInteger('role_id');
            $table->boolean('is_approved')->default(false);
            $table->bigInteger('superadmin_id')->nullable();
            $table->timestamps();
        });

        // Copy data from old table to new table
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            DB::table('users_new')->insert([
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'remember_token' => $user->remember_token,
                'admin_id' => $user->admin_id,
                'role_id' => $user->role_id,
                'is_approved' => $user->is_approved,
                'superadmin_id' => $user->superadmin_id,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }

        // Drop old table and rename new table
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::drop('users');
        Schema::rename('users_new', 'users');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the original structure (this is complex, so we'll just recreate the table)
        Schema::dropIfExists('users');
        
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('admin_id')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->timestamps();
            $table->string('first_name');
            $table->string('last_name');
            $table->bigInteger('role_id');
            $table->boolean('is_approved')->default(false);
            $table->bigInteger('superadmin_id')->nullable();
        });
    }
};