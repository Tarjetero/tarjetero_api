<?php

namespace App\Services\Data;

use App\Constantes\FiltroGlobal;
use App\Constantes\Plataforma;
use App\Constantes\StatusGlobal;
use App\Repository\Data\CanalCaptacionRepoData;
use App\Repository\Data\EmailRepoData;
use App\Repository\Data\EtiquetaRepoData;
use App\Repository\Data\LeadRepoData;
use App\Repository\Data\OportunidadRepoData;
use App\Repository\Data\PaisRepoData;
use App\Repository\Data\PlataformaRepoData;
use App\Repository\Data\SeguimientoRepoData;
use App\Repository\Data\TelefonoRepoData;
use App\Repository\Data\UsuarioRepoData;
use App\Utilerias\TextoUtils;
use Exception;
use Illuminate\Support\Facades\Log;
use stdClass;

class LeadServiceData
{
    /**
     * Metodo para listar clientes
     * @param $filtros
     * @return array $clientes
     * @throws Exception
     */
    public static function listarGestor($filtros)
    {
        try {

            return ClienteRepoData::listarGestor($filtros);
        } catch (Exception $e) {
            TextoUtils::agregarLogError($e, "LeadServiceData::listar()");
            throw new Exception("Problema en servicio listar leads. {$e->getMessage()} ", 300, $e);
        }
    }
}
