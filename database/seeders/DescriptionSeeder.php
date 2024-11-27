<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Description;

class DescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some sample descriptions
        Description::create([
            'text' => 'I am a software engineer',
            'user_id' => '1',
        ]);

        Description::create([
            'text' => 'study at Damascus university',
            'user_id' => '1',

        ]);

        Description::create([
            'text' => 'specialized in backend development',
            'user_id' => '1',

        ]);

        Description::create([
            'text' => 'lives in Damascus, Syria',
            'user_id' => '1',

        ]);

        // user 2
         // Create some sample descriptions
         Description::create([
            'text' => 'I am a software engineer',
            'user_id' => '2',
        ]);

        Description::create([
            'text' => 'study at Damascus university',
            'user_id' => '2',

        ]);

        Description::create([
            'text' => 'specialized in backend development',
            'user_id' => '2',

        ]);

        Description::create([
            'text' => 'lives in Damascus, Syria',
            'user_id' => '2',

        ]);


        // user 3
        Description::create([
            'text' => 'I am a software engineer',
            'user_id' => '3',
        ]);

        Description::create([
            'text' => 'study at Damascus university',
            'user_id' => '3',

        ]);

        Description::create([
            'text' => 'specialized in backend development',
            'user_id' => '3',

        ]);

        Description::create([
            'text' => 'lives in Damascus, Syria',
            'user_id' => '3',

        ]);

        // user 4
        Description::create([
            'text' => 'I am a software engineer',
            'user_id' => '4',
        ]);

        Description::create([
            'text' => 'study at Damascus university',
            'user_id' => '4',

        ]);

        Description::create([
            'text' => 'specialized in backend development',
            'user_id' => '4',

        ]);

        Description::create([
            'text' => 'lives in Damascus, Syria',
            'user_id' => '4',

        ]);

        // user 5
        Description::create([
            'text' => 'I am a software engineer',
            'user_id' => '5',
        ]);

        Description::create([
            'text' => 'study at Damascus university',
            'user_id' => '5',

        ]);

        Description::create([
            'text' => 'specialized in backend development',
            'user_id' => '5',

        ]);

        Description::create([
            'text' => 'lives in Damascus, Syria',
            'user_id' => '5',

        ]);

        // user 6
        Description::create([
            'text' => 'I am a software engineer',
            'user_id' => '6',
        ]);

        Description::create([
            'text' => 'study at Damascus university',
            'user_id' => '6',

        ]);

        Description::create([
            'text' => 'specialized in backend development',
            'user_id' => '6',

        ]);

        Description::create([
            'text' => 'lives in Damascus, Syria',
            'user_id' => '6',

        ]);






    }
}
