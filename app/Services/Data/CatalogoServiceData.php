<?php

namespace App\Services\Data;

use App\Repository\Data\CatalogoRepoData;

class CatalogoServiceData
{
  /**
   * listarMarcasTarjetas
   *
   * @param  mixed $filtros
   * @return array
   */
  public static function listarMarcasTarjetas(array $filtros): array
  {
    return CatalogoRepoData::listarMarcasTarjetas($filtros);
  }
}
