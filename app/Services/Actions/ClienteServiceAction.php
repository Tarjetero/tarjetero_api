<?php

namespace App\Services\Actions;

use stdClass;
use Exception;
use Throwable;
use App\Helpers\Constantes;
use App\Utilerias\Utilerias;
use App\Services\BO\ClienteBO;
use App\Services\BO\SuscripcionBO;
use Illuminate\Support\Facades\DB;
use App\Services\Data\AuthServiceData;
use App\Repository\Data\ClienteRepoData;
use App\Repository\Actions\ClienteRepoAction;
use App\Repository\Actions\SuscripcionRepoAction;

class ClienteServiceAction
{
    /**
     * Metodo para agregar cliente
     * @param array $datos
     * @return stdClass
     * @throws Exception
     */
    public static function registroCliente($datos)
    {
      try {
        DB::beginTransaction();
        // Se valida email
        $email = ClienteRepoData::validarEmail($datos['email']);
        if(!empty($email))
            throw new Exception('Ya existe una cuenta con el correo ' . $datos['email']);

        $usuario = ClienteRepoData::validarUsuario($datos['usuario']);
        if(!empty($usuario))
            throw new Exception('Ya existe una cuenta con el usuario ' . $datos['usuario']);

        // Se arma insert de nuevo cliente
        $insert = ClienteBO::armarInsert($datos);
        ClienteRepoAction::agregarCliente($insert);
        $datos['clienteId'] = $insert['cliente_id'];
        
        // Se arma insert de perfil de cliente
        $insertPerfil = ClienteBO::armarInsertPerfil($datos);
        ClienteRepoAction::agregarClientePerfil($insertPerfil);
        
        // Se arma insert de 1er suscripcion de cliente
        $insertSuscripcion = SuscripcionBO::armarInsert($datos);
        SuscripcionRepoAction::agregarSuscripcion($insertSuscripcion);

        //Se realiza inicio de sesión despues de registrarse
        $authData = AuthServiceData::autenticar($datos['email'], $datos['password'], $datos['ip'], $datos['userAgent']);

        DB::commit();

        return $authData;
      } catch (Throwable $th) {
        DB::rollBack();
        throw $th;
      }
    }

    /**
     * Metodo para editar foto de cliente
     * @param array $datos
     * @throws Exception
     */
    public static function editarFoto($datos)
    {
      DB::beginTransaction();

        // Se arma update de foto de cliente
        $datos['tipo'] = Constantes::EDITAR_FOTO;
        $update = ClienteBO::editarPerfil($datos);
        ClienteRepoAction::actualizar($update, $datos['clienteId']);

      DB::commit();

      return;
    }

    /**
     * Metodo para editar password de cliente
     * @param array $datos
     * @throws Exception
     */
    public static function editarPassword($datos)
    {
      DB::beginTransaction();

        // Se arma update del password cliente
        $datos['tipo'] = Constantes::EDITAR_PASSWORD;
        $update = ClienteBO::editarPerfil($datos);
        ClienteRepoAction::actualizar($update, $datos['clienteId']);

      DB::commit();

      return;
    }
}
