<?php

namespace App\Http\Controllers;

use App\Services\Data\TarjetaServiceData;
use App\Helpers\ApiResponse;
use App\Helpers\CodeResponse;
use Illuminate\Http\Request;
use Throwable;

class TarjetaController extends Controller
{
  /**
   * Obtiener listado de tarjetas
   * @param Request $request
   * @return mixed
   */
  public function getTarjetas(Request $request)
  {
    try {
      $filtros = $request->all();
      $tarjetas = TarjetaServiceData::listar($filtros);
      return response(ApiResponse::build(CodeResponse::EXITO, "Tarjetas obtenidas correcamente.", $tarjetas));
    } catch (Throwable $e) {
      throw $e;
    }
  }
}
