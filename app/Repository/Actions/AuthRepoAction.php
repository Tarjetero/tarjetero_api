<?php

namespace App\Repository\Actions;

use App\Constantes;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthRepoAction
{
  /**
   * actualizar sesion cliente
   *
   * @param  mixed $update
   * @return void
   */
  public static function actualizarClienteSesion($clienteId, array $update)
  {
      DB::table('clientes_perfil')
          ->where('cliente_id', $clienteId)
          ->update($update);
  }
}
