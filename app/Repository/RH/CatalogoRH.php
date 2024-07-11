<?php

namespace App\Repository\RH;

use App\Helpers\Constantes;
use Illuminate\Database\Query\Builder;

class CatalogoRH
{
  /**
   * getListarMarcasTarjetasFiltros
   *
   * @param  mixed $query
   * @param  mixed $filtros
   * @return void
   */
  public static function getListarMarcasTarjetasFiltros(Builder &$query, array $filtros)
  {
    if (!empty($filtros['status'])) {
      $query->whereIn('cmt.status', $filtros['status']);
    }

    if (!empty($filtros['ordenar'])) {
      switch ($filtros['ordenar']) {
        case Constantes::NOMBRE_DESC:
          $query->orderByDesc('cmt.nombre');
          break;

        default:
          $query->orderBy('cmt.nombre');
          break;
      }
    } else {
      $query->orderBy('cmt.nombre');
    }
  }
}
