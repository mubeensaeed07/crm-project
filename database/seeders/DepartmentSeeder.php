<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Human Resources',
                'code' => 'HR',
                'description' => 'Human Resources Department',
                'is_active' => true
            ],
            [
                'name' => 'Information Technology',
                'code' => 'IT',
                'description' => 'Information Technology Department',
                'is_active' => true
            ],
            [
                'name' => 'Finance',
                'code' => 'FIN',
                'description' => 'Finance and Accounting Department',
                'is_active' => true
            ],
            [
                'name' => 'Marketing',
                'code' => 'MKT',
                'description' => 'Marketing and Sales Department',
                'is_active' => true
            ],
            [
                'name' => 'Operations',
                'code' => 'OPS',
                'description' => 'Operations Department',
                'is_active' => true
            ],
            [
                'name' => 'Support',
                'code' => 'SUP',
                'description' => 'Support Department',
                'is_active' => true
            ]
        ];

        foreach ($departments as $department) {
            \App\Models\Department::create($department);
        }
    }
}
