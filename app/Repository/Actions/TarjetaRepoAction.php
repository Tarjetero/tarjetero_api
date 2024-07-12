<?php

namespace App\Repository\Actions;

use App\Constantes;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TarjetaRepoAction
{
  /**
   * INSERT tarjeta
   *
   * @param  mixed $insert
   * @return void
   */
  public static function agregarTarjeta(array $insert)
  {
    $id = DB::table('tarjetas')
      ->insertGetId($insert, 'tarjeta_id')
    ;

    return $id;
  }

  /**
   * INSERT tarjeta_historico
   *
   * @param  mixed $insert
   * @return void
   */
  public static function agregarHistoricoTarjeta(array $insert)
  {
    $id = DB::table('tarjetas_historico')
      ->insertGetId($insert, 'tarjeta_historico_id')
    ;

    return $id;
  }

  /**
   * Método que ejecuta un update en tarjetas
   * @param  mixed $update
   * @param  mixed $tarjetaId
   * @param  mixed $clienteId
   * @return void
   */
	public static function actualizarTarjeta(array $update, $tarjetaId, $clienteId){
        try{
            DB::table('tarjetas')
            ->where('tarjeta_id', $tarjetaId)
            ->where('cliente_id', $clienteId)
            ->update($update);
    
        }catch(QueryException $e){
            throw $e;
        }
      }
}
