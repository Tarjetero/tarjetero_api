<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\CodeResponse;
use App\Helpers\Constantes;
use App\Services\Data\CatalogoServiceData;
use Illuminate\Http\Request;
use Throwable;

class CatalogoController extends Controller
{
  /**
   * Obtiener listado de catalogo marcas tarjetas
   * @param Request $request
   * @return mixed
   */
  public function getMarcasTarjetas(Request $request)
  {
    try {
      $filtros = $request->all();
      $filtros['status'] ??= [Constantes::STATUS_ACTIVO];
      $tarjetas = CatalogoServiceData::listarMarcasTarjetas($filtros);
      return response(ApiResponse::build(CodeResponse::EXITO, "Marcas de tarjetas obtenidas correcamente.", $tarjetas));
    } catch (Throwable $e) {
      throw $e;
    }
  }
}
