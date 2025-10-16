<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;

class UpdateModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update the second module from "Lead Tracking" to "FINANCE"
        $module = Module::where('name', 'Lead Tracking')->first();
        if ($module) {
            $module->update([
                'name' => 'FINANCE',
                'description' => 'Financial Management System',
                'icon' => 'bx bx-money'
            ]);
            echo "Updated module: Lead Tracking -> FINANCE\n";
        } else {
            // If module doesn't exist, create it
            Module::create([
                'name' => 'FINANCE',
                'description' => 'Financial Management System',
                'icon' => 'bx bx-money'
            ]);
            echo "Created new module: FINANCE\n";
        }
    }
}
