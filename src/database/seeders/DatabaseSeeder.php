<?php

namespace Database\seeders;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         Project::factory(10)->create();
         Task::factory(10)->create();
    }
}
