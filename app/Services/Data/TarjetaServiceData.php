<?php

namespace App\Services\Data;

use App\Repository\Data\TarjetaRepoData;

class TarjetaServiceData
{
  /**
   * listar
   *
   * @param  mixed $filtros
   * @return array
   */
  public static function listar(array $filtros): array
  {
    return TarjetaRepoData::listar($filtros);
  }

  /**
   * obtener objeto de registro de tarjeta
   *
   * @param  mixed $filtros
   * @return object
   */
  public static function obtenerInfo(array $filtros): object
  {
    $tarjeta = TarjetaRepoData::obtenerTarjeta($filtros);
    return  $tarjeta;
  }
}
