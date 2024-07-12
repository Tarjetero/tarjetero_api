<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Helpers\Msg;
use App\Helpers\ApiResponse;
use App\Utilerias\Utilerias;
use Illuminate\Http\Request;
use App\Helpers\CodeResponse;
use App\Services\Data\TarjetaServiceData;
use Illuminate\Support\Facades\Validator;
use App\Services\Actions\TarjetaServiceAction;

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

  /**
   * Obtiener listado de tarjetas
   * @param Request $request
   * @return mixed
   */
  public function getTarjetaInfo(Request $request)
  {
    try {
      $filtros = $request->all();
      $tarjeta = TarjetaServiceData::obtenerInfo($filtros);

      return response(ApiResponse::build(CodeResponse::EXITO, "Tarjeta obtenida correcamente.", $tarjeta));
    } catch (Throwable $e) {
      throw $e;
    }
  }

  /**
   * Agrega un nuevo cliente
   * @param Request $request
   * @return mixed
   */
  public function agregar(Request $request){
    try{

        $reglasValidacion = [
          'clienteId'       => 'required',
          'numero'          => 'required',
          'titular'         => 'required',
          'titulo'          => 'required',
          'color'           => 'required',
          'marcaTarjetaId'  => 'required',
        ];

        $validation = Validator::make($request->all(),$reglasValidacion, Msg::VALIDATIONS);

        if($validation->fails())
            throw new Exception(Utilerias::obtenerMensajesValidator($validation->getMessageBag()));

        $datos = $request->all();
        $result = TarjetaServiceAction::agregarTarjeta($datos);

        return response(ApiResponse::build(CodeResponse::EXITO,"Operación realizada correctamente.", $result));

    } catch (Throwable $e) {
        throw $e;
    }
  }

  /**
   * Agrega un nuevo cliente
   * @param Request $request
   * @return mixed
   */
  public function editar(Request $request){
    try{

        $reglasValidacion = [
          'tarjetaId'       => 'required',
          'clienteId'       => 'required',
          'numero'          => 'required',
          'titular'         => 'required',
          'titulo'          => 'required',
          'color'           => 'required',
          'marcaTarjetaId'  => 'required',
        ];

        $validation = Validator::make($request->all(),$reglasValidacion, Msg::VALIDATIONS);

        if($validation->fails())
            throw new Exception(Utilerias::obtenerMensajesValidator($validation->getMessageBag()));

        $datos = $request->all();
        $result = TarjetaServiceAction::editarTarjeta($datos);

        return response(ApiResponse::build(CodeResponse::EXITO,"Operación realizada correctamente.", $result));

    } catch (Throwable $e) {
        throw $e;
    }
  }
}
