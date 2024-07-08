<?php

namespace App\Repository\RH;

use Illuminate\Database\Query\Builder;

class TarjetaRH
{  
  /**
   * getListarFiltros
   *
   * @param  mixed $query
   * @param  mixed $filtros
   * @return void
   */
  public static function getListarFiltros(Builder &$query, array $filtros)
  {
    if (!empty($filtros['clienteId'])) {
      $query->where('t.cliente_id', $filtros['clienteId']);
    }
  }
}
