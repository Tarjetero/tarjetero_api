<?php

namespace App\Services\Actions;

use App\Helpers\Constantes;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Repository\Actions\TarjetaRepoAction;
use App\Repository\Data\TarjetaRepoData;
use App\Services\BO\TarjetaBO;

class TarjetaServiceAction
{
    /**
     * Metodo para agregar tarjeta de cliente
     * @param array $datos
     * @return object $respuesta
     * @throws Exception
     */
    public static function agregarTarjeta($datos)
    {
        $tarjeta = DB::transaction(function() use($datos){
            // Se valida numero de tarjeta
            $tarjeta = TarjetaRepoData::validarNumero($datos['clienteId'], $datos['numero']);
            if(!empty($tarjeta))
                throw new Exception('Ya tienes una tarjeta con el número ' . $datos['numero']);

            // Se arma insert de nueva tarjeta de cliente
            $insert = TarjetaBO::armarInsert($datos);
            TarjetaRepoAction::agregarTarjeta($insert);
            $datos['tarjetaId'] = $insert['tarjeta_id'];

            return $insert;
        });

        return (object)$tarjeta;
    }

    /**
     * Metodo para editar datos de tarjeta de cliente
     * @param array $datos
     * @throws Exception
     */
    public static function editarTarjeta($datos)
    {
      DB::beginTransaction();

        $tarjeta = TarjetaRepoData::obtenerTarjeta(
          [
            'clienteId' => $datos['clienteId'],
            'status' => [Constantes::STATUS_ACTIVO]
          ]
        );

        // Se arma update de cliente
        $update = TarjetaBO::armarUpdate($datos);
        TarjetaRepoAction::actualizarTarjeta($update, $datos['tarjetaId'], $datos['clienteId']);

        $insert = TarjetaBO::armarTarjetaHistorico($tarjeta);
        TarjetaRepoAction::agregarHistoricoTarjeta($insert);

      DB::commit();

      return $tarjeta;
    }
}
