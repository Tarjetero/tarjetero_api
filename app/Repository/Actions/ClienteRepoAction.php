<?php

namespace App\Repository\Actions;

use App\Constantes;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClienteRepoAction
{
  /**
   * INSERT cliente
   *
   * @param  mixed $insert
   * @return void
   */
  public static function agregarCliente(array $insert)
  {
    $id = DB::table('clientes')
      ->insertGetId($insert, 'cliente_id')
    ;

    return $id;
  }

  /**
   * INSERT clientes_perfiles
   *
   * @param  mixed $insert
   * @return void
   */
  public static function agregarClientePerfil(array $insert)
  {
    $id = DB::table('clientes_perfil')
      ->insertGetId($insert, 'cliente_perfil_id')
    ;

    return $id;
  }

  /**
   * Método que ejecuta un update en clientes
   * @param  mixed $update
   * @param  mixed $clienteId
   * @return void
   */
	public static function actualizar(array $update, $clienteId)
  {
      DB::table('clientes')
      ->where('cliente_id', $clienteId)
      ->update($update);

      return;
  }

  /**
   * Método que ejecuta un update en clientes_perfil
   * @param  mixed $update
   * @param  mixed $clienteId
   * @return void
   */
	public static function actualizarPerfil(array $update, $clienteId)
  {
      DB::table('clientes_perfil')
      ->where('cliente_id', $clienteId)
      ->update($update);

      return;
  }
}
