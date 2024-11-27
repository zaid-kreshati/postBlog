<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create parent categories
        $parentCategory1 = Category::create([
            'name' => 'public',
            'parent_id' => null,
        ]);

        $parentCategory2 = Category::create([
            'name' => 'private',
            'parent_id' => null,
        ]);

        // Create parent categories
        $parentCategory3 = Category::create([
            'name' => 'general',
            'parent_id' => null,
        ]);

        $parentCategory4 = Category::create([
            'name' => 'social',
            'parent_id' => null,
        ]);

        // Create parent categories
        $parentCategory5 = Category::create([
            'name' => 'sport',
            'parent_id' => null,
        ]);

        $parentCategory6 = Category::create([
            'name' => 'politic',
            'parent_id' => null,
        ]);

        // Create child categories and associate with parent
        Category::create([
            'name' => 'buisness',
            'parent_id' => $parentCategory1->id,
        ]);

        Category::create([
            'name' => 'travel',
            'parent_id' => $parentCategory1->id,
        ]);

        Category::create([
            'name' => 'cars',
            'parent_id' => $parentCategory2->id,
        ]);

        Category::create([
            'name' => 'food',
            'parent_id' => $parentCategory2->id,
        ]);

        Category::create([
            'name' => 'health',
            'parent_id' => $parentCategory3->id,
        ]);

        Category::create([
            'name' => 'friends',
            'parent_id' => $parentCategory3->id,
        ]);



        Category::create([
            'name' => 'sport',
            'parent_id' => $parentCategory4->id,
        ]);

        Category::create([
            'name' => 'drama',
            'parent_id' => $parentCategory4->id,
        ]);

        Category::create([
            'name' => 'comedy',
            'parent_id' => $parentCategory5->id,
        ]);

        Category::create([
            'name' => 'football',
            'parent_id' => $parentCategory5->id,
        ]);

        Category::create([
            'name' => 'basketball',
            'parent_id' => $parentCategory6->id,
        ]);

        Category::create([
            'name' => 'flower',
            'parent_id' => $parentCategory6->id,
        ]);



    }
}
