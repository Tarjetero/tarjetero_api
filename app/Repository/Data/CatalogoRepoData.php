<?php

namespace App\Repository\Data;

use App\Repository\RH\CatalogoRH;
use Illuminate\Support\Facades\DB;

class CatalogoRepoData
{
  /**
   * listarMarcasTarjetas
   *
   * @param  mixed $filtros
   * @return array
   */
  public static function listarMarcasTarjetas(array $filtros): array
  {
    $query = DB::table('cat_marcas_tarjetas AS cmt')
      ->select(
        'cmt.*'
      );

    CatalogoRH::getListarMarcasTarjetasFiltros($query, $filtros);

    return $query->get()->toArray();
  }
}
