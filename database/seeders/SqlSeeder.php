<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class SqlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('inserts/careers.sql');
        $sql = File::get($path);
        DB::unprepared($sql);

        $path = database_path('inserts/conditions.sql');
        $sql = File::get($path);
        DB::unprepared($sql);

        $path = database_path('inserts/positions.sql');
        $sql = File::get($path);
        DB::unprepared($sql);

        $path = database_path('inserts/offices.sql');
        $sql = File::get($path);
        DB::unprepared($sql);

        $path = database_path('inserts/workers.sql');
        $sql = File::get($path);
        DB::unprepared($sql);

        $path = database_path('inserts/professors.sql');
        $sql = File::get($path);
        DB::unprepared($sql);
    }
}
