<?php

namespace App\Services\Actions;

use stdClass;
use Exception;
use App\Utilerias\TextoUtils;
use App\Services\BO\ClienteBO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repository\Data\ClienteRepoData;
use App\Repository\Actions\ClienteRepoAction;
use App\Repository\Actions\SuscripcionRepoAction;
use App\Services\BO\SuscripcionBO;
use App\Services\Data\AuthServiceData;

class ClienteServiceAction
{
    /**
     * Metodo para agregar cliente
     * @param array $datos
     * @return object $respuesta
     * @throws Exception
     */
    public static function registroCliente($datos)
    {
        return DB::transaction(function() use($datos){
            // Se valida email
            $email = ClienteRepoData::validarEmail($datos['email']);
            if(!empty($email))
                throw new Exception('Ya existe una cuenta con el correo ' . $datos['email']);

            $usuario = ClienteRepoData::validarUsuario($datos['usuario']);
            if(!empty($usuario))
                throw new Exception('Ya existe una cuenta con el usuario ' . $datos['usuario']);

            // Se arma insert de nuevo cliente
            $insert = ClienteBO::armarInsert($datos);
            $clienteId = ClienteRepoAction::agregarCliente($insert);

            // Se arma insert de perfil de cliente
            $insertPerfil = ClienteBO::armarInsertPerfil($datos);
            ClienteRepoAction::agregarClientePerfil($insertPerfil);

            // Se arma insert de 1er suscripcion de cliente
            $insertSuscripcion = SuscripcionBO::armarInsert($datos);
            SuscripcionRepoAction::agregarSuscripcion($insertSuscripcion);

            //Se realiza inicio de sesión despues de registrarse
            $authData = AuthServiceData::autenticar($email, $datos['password'], $datos['ip'], $datos['userAgent']);

            return $authData;
        });
    }

    /**
     * Metodo para editar datos de cliente
     * @param array $datos
     * @throws Exception
     */
    public static function editarClienteInfo($datos)
    {
        try {
            DB::beginTransaction();

            // Se arma update de cliente
            $update = ClienteBO::armarUpdate($datos);
            ClienteRepoAction::actualizar($update, $datos['clienteId']);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            TextoUtils::agregarLogError($e, "ClienteServiceAction::editar()");
            throw new Exception("Problema en servicio editar cliente. {$e->getMessage()}", 300, $e);
        }
    }
}
