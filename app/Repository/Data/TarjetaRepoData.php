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
      ->select('t.*');

    TarjetaRH::getListarFiltros($query, $filtros);

    return $query->get()->toArray();
  }
}
