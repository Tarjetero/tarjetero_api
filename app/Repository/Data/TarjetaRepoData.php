<?php

namespace App\Repository\Data;

use App\Repository\RH\TarjetaRH;
use Illuminate\Support\Facades\DB;

class TarjetaRepoData
{
  /**
   * listar
   *
   * @param  mixed $filtros
   * @return array
   */
  public static function listar(array $filtros): array
  {
    $query = DB::table('tarjetas AS t')
      ->select(
        't.*',
        'cmt.icono'
      )
      ->leftJoin('cat_marcas_tarjetas AS cmt', 'cmt.marca_tarjeta_id', 't.marca_tarjeta_id')
      ;

    TarjetaRH::getListarFiltros($query, $filtros);

    return $query->get()->toArray();
  }
}
