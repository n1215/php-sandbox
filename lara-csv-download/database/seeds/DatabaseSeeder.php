<?php

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
        \App\Models\Post::query()->delete();
        \App\Models\Author::query()->delete();

        collect(range(1, 3))
            ->each(function (int $id) {
                factory(\App\Models\Author::class)->create(['id' => $id]);
            });

        collect(range(1, 10000))
            ->each(function (int $id) {
                factory(\App\Models\Post::class)->create(['id' => $id]);
            });
    }
}
