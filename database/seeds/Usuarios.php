<?php


use Phinx\Seed\AbstractSeed;

class Usuarios extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'nombre' => 'root',
                'apellido' => 'test',
                'correo' => 'admin@root.com',
                'password' => '123456789',
                'telefono' => '324567890',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'id_role' => 1

            ],
            [
                'nombre' => 'gestores',
                'apellido' => 'test',
                'correo' => 'gestores@root.com',
                'password' => '12345678',
                'telefono' => '324567890',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'id_role' => 2

            ],
            [
                'nombre' => 'auditor',
                'apellido' => 'test',
                'correo' => 'auditor@root.com',
                'password' => '1455666777',
                'telefono' => '3245656789',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'id_role' => 3

            ],
            [
                'nombre' => 'basic',
                'apellido' => 'test',
                'correo' => 'basic@root.com',
                'password' => '123456789',
                'telefono' => '324567896',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'id_role' => 4

            ]
        ];
        $this->table('usuarios')
            ->insert($data)
            ->save();
    }
}
