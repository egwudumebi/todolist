<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Using specific category names
         $categories = [
            'Work',
            'Personal',
            'Shopping',
            'Fitness',
            'Study',
            'Chores',
            'Appointments',
            'Hobbies',
            'Urgent',
            'Miscellaneous'
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
