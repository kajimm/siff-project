<?php
namespace App\Controllers;

use Core\Render\RendererInterface as Render;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \App\models\Usuarios;
use \Core\Controller\Controller;

/**
 *
 */
class UsersController extends Controller
{
    private $render;
   

    public function __construct(Render $renderer)
    {
        $this->render = $renderer;
    }

    public function index(Request $request): Response
    {
        $template = $this->render->render('users/usuarios', array());
        return $this->response($template, 200);
    }

    public function users(){
       return Usuarios::all();
    }


    public function edit(Request $request): Response{

    }

    public function details(Request $request, array $arg): Response
    {

        $detail = Usuarios::where('id_usuario', '=', $arg['id'])->get();
        
        $join = Usuarios::select('usuarios.nombre AS Usuario', 'roles.nombre AS Rol', 'permisos.modulo AS Modulo', 'acciones.operacion AS Accion')
                        ->join('accion_role', 'accion_role.id_role', '=', 'usuarios.id_role')
                        ->join('roles', 'roles.id_role', '=', 'accion_role.id_role')
                        ->join('acciones', 'acciones.id_accion', '=', 'accion_role.id_accion')
                        ->join('permisos', 'permisos.id_permiso', '=', 'acciones.id_permiso')
                        ->where('usuarios.id_usuario', '=', $arg['id'])
                        ->get();

        

        $tem = $this->render->render('users/view', compact('detail', 'join'));
        return $this->response($tem, 200);

    }

    public function delete(Request $request): Response
    {
        
    }

}
