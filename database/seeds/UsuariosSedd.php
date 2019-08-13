<?php

use Phinx\Seed\AbstractSeed;

class UsuariosSedd extends AbstractSeed
{
    public function run()
    {
        $users = [];

        date_default_timezone_set('America/Bogota');

        $faker = \Faker\Factory::create('es_ES');

        for ($i = 0; $i < 50; ($i++) - 1) {
            $date    = $faker->unixTime('now');
            $users[] = [
                'username'      => $faker->userName,
                'password'      => sha1($faker->address),
                'password_salt' => sha1($faker->address),
                'email'         => $faker->email,
                'first_name'    => $faker->name,
                'last_name'     => $faker->lastName,
                'created_at'    => date('Y-m-d H:i:s', $date),
                'updated_at'    => date('Y-m-d H:i:s', $date),
            ];
        }

        $this->table('usuarios')
            ->insert($users)
            ->save();
    }
}
