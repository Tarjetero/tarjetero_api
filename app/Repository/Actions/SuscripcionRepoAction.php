<?php

namespace App\Repository\Actions;

use App\Constantes;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SuscripcionRepoAction
{
  /**
   * INSERT cliente
   *
   * @param  mixed $insert
   * @return void
   */
  public static function agregarSuscripcion(array $insert)
  {
    $id = DB::table('clientes_suscripciones')
      ->insertGetId($insert, 'cliente_suscripcion_id')
    ;

    return $id;
  }
}
