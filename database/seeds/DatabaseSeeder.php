<?php

use Illuminate\Database\Seeder;
use App\Repositories\Repository;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        touch('database/database.sqlite');
    }

}
