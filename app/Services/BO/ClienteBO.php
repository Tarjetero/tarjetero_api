<?php

namespace App\Services\BO;

use DateTime;
use Exception;
use Carbon\Carbon;
use App\Helpers\Constantes;
use App\Util\Utils;
use App\Repository\Data\ClienteRepoData;
use App\Utilerias\Utilerias;
use Litipk\BigNumbers\Decimal;
use Illuminate\Support\Facades\DB;
use stdClass;

class ClienteBO
{
    /**
     * Se arma objeto de cliente que se insertara en base de datos
     *
     * @param  array   $datos
     * @return mixed
     */
    public static function armarInsert(array $datos): array
    {
        $cliente = [];

        $cliente['cliente_id']      = Utilerias::generateId();
        $cliente['nombre']          = $datos['nombre'];
        $cliente['apellidos']       = $datos['apellidos'];
        $cliente['email']           = $datos['email'];
        $cliente['status']          = Constantes::STATUS_ACTIVO;
        $cliente['registro_fecha']  = Utilerias::now();

        return $cliente;
    }

    /**
     * Se arma objeto de cliente que se insertara en base de datos
     *
     * @param  array   $datos
     * @return mixed
     */
    public static function armarInsertPerfil(array $datos): array
    {
        $insert = [];

        $insert['cliente_perfil_id']    = Utilerias::generateId();
        $insert['cliente_id']           = $datos['clienteId'];
        $insert['password']             = Utilerias::generarPassword($datos['password']);
        $insert['foto']                 = null;
        $insert['pin']                  = Utilerias::generateId();
        $insert['usuario']              = $datos['usuario'];
        $insert['registro_fecha']       = Utilerias::now();

        return $insert;
    }
}