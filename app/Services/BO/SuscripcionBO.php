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

class SuscripcionBO
{
    /**
     * Se arma objeto de clientes_suscripciones que se insertara en base de datos
     *
     * @param  array   $datos
     * @return mixed
     */
    public static function armarInsert(array $datos): array
    {
        $suscripcion = [];

        $suscripcion['cliente_suscripcion_id']  = Utilerias::generateId();
        $suscripcion['cliente_id']              = $datos['clienteId'];
        $suscripcion['dias_activa']             = Constantes::DIAS_SUSCRIPCION;
        $suscripcion['fecha_fin']               = Utilerias::sumDiasAFechaSinTiempo(Utilerias::now(), Constantes::DIAS_SUSCRIPCION);
        $suscripcion['fecha_pago']              = Utilerias::now();
        $suscripcion['pagado']                  = Constantes::SUSCRIPCION_PAGADA;
        $suscripcion['token_referencia']        = null;
        $suscripcion['status']                  = Constantes::STATUS_ACTIVO;
        $suscripcion['monto']                   = 0;
        $suscripcion['registro_fecha']          = Utilerias::now();

        return $suscripcion;
    }
}