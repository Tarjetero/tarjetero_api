<?php

namespace App\Repository\Data;

use stdClass;
use App\Helpers\Constantes;
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

  /**
   * listar
   *
   * @param  mixed $filtros
   * @return object
   */
  public static function obtenerTarjeta(array $filtros): object
  {
    $query = DB::table('tarjetas AS t')
      ->select(
        't.*',
        'cmt.icono'
      )
      ->leftJoin('cat_marcas_tarjetas AS cmt', 'cmt.marca_tarjeta_id', 't.marca_tarjeta_id')
      ;

    TarjetaRH::getListarFiltros($query, $filtros);

    $tarjeta = $query->get()->first();

    return $tarjeta ?: new stdClass();
  }

  /**
   * Metodo para validar que no exista el mismo numero de tarjeta por cliente
   * @param string $clienteId
   * @param string $numero
   * @throws Exception
   */
  public static function validarNumero(string $clienteId, string $numero)
  {
    $tarjeta = DB::table('tarjetas AS t')
        ->select(
            't.cliente_id',
            't.numero',
            't.titular',
            't.titulo',
            't.status'
        )
        ->where('t.status', Constantes::STATUS_ACTIVO)
        ->where('t.cliente_id', $clienteId)
        ->where('t.numero', $numero)
        ->get()->first();

    return $tarjeta;
  }
}
