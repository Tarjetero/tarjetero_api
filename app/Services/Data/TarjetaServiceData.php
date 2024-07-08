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
}
