<?php

namespace Database\Seeders;

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
        $this->call(Settings::class);
        $this->call(Permissions::class);
        $this->call(User::class);
        $this->call(Catalogs::class);
        $this->call(Plans::class);
    }
}
