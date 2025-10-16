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
        Schema::create('user_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Insert default user types
        DB::table('user_types')->insert([
            ['name' => 'SuperAdmin', 'description' => 'System Administrator', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Admin', 'description' => 'Administrator', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Supervisor', 'description' => 'Supervisor', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'User', 'description' => 'Regular User', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_types');
    }
};