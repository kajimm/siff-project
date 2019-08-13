Crear migraciones:
	env ENV=dev ./vendor/bin/phinx create Migracion
	env ENV=dev ./vendor/bin/phinx migrate   ---> ejecutar migracion

	//seeds

	env ENV=dev ./vendor/bin/phinx seed:create MyNewSeeder
	env ENV=dev ./vendor/bin/phinx seed::run

~~~sql
//consultas joint para manejo de permisos

select * from usuarios
left join roles on roles.id_role = usuarios.id_role

//selecionar los datos organizados

select usuarios.nombre as usuario, roles.nombre as Rol,permisos.modulo as Modulo, acciones.operacion as Accion from usuarios
left join accion_role on accion_role.id_role = usuarios.id_role
left join roles on roles.id_role = accion_role.id_role
left join acciones on acciones.id_accion = accion_role.id_accion
left join permisos on permisos.id_permiso = acciones.id_permiso
	
~~~php

//enviar correos electronicos

$mailer->subject('Mensaje final')
                ->from(["correo" => "nombre"])
                ->to(["correo" => "nombre"])
                ->cc(["correo" => "nombre"])
                ->bcc(["correo" => "nombre"])
                ->attached('path/file')
                ->messague('<h1>Esta es la ultima</h1>')
                ->send();