<?php

namespace App\Services\Data;

use stdClass;
use Exception;
use App\Utilerias\TextoUtils;
use Illuminate\Support\Facades\Log;
use App\Repository\Data\ClienteRepoData;

class ClienteServiceData
{

    /**
     * Metodo para listar clientes
     * @param $filtros
     * @return array $clientes
     * @throws Exception
     */
    public static function getClienteInfo($filtros)
    {
        try {
            $cliente = ClienteRepoData::getClienteInfo($filtros);
            return $cliente;
        } catch (Exception $e) {
            TextoUtils::agregarLogError($e, "ClienteServiceData::listar()");
            throw new Exception("Problema en servicio obtener cliente. {$e->getMessage()} ", 300, $e);
        }
    }
}
