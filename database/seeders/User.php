<?php

namespace Database\Seeders;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;

class User extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $user = \App\Models\User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Administrador',
            'password' => 'password',
            'locale' => 'es_ES',
            'enabled' => '1',
        ]);
        $user->roles()->sync([1]);
    }
}
