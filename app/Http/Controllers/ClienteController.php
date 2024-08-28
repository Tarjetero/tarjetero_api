<?php

namespace App\Http\Controllers;

use Exception;
use App\Helpers\Msg;
use App\Helpers\ApiResponse;
use App\Utilerias\Utilerias;
use Illuminate\Http\Request;
use App\Helpers\CodeResponse;
use App\Services\Data\ClienteServiceData;
use Illuminate\Support\Facades\Validator;
use App\Services\Actions\ClienteServiceAction;
use Throwable;
use App\Exceptions\ValidateRequestException;

class ClienteController
{
  /**
   * Obtiener datos de cliente
   * @param Request $request
   * @return mixed
   */
  public function getCliente(Request $request){
      try{

          $filtros = $request->all();

          $cliente = ClienteServiceData::getClienteInfo($filtros);

          return response(ApiResponse::build(CodeResponse::EXITO,"Datos listados correctamente.", $cliente));

      }catch(Exception $e){
          return response(ApiResponse::build(CodeResponse::ERROR,$e->getMessage()));
      }
  }

  /**
   * Agrega un nuevo cliente
   * @param Request $request
   * @return mixed
   */
  public function registro(Request $request){
      try{

          $reglasValidacion = [
              'email'             => 'required',
              'usuario'           => 'required',
              'password'          => 'required',
              'passwordConfirm'   => 'required',
              'nombre'            => 'required',
              'apellidos'         => 'required',
          ];

          $validation = Validator::make($request->all(),$reglasValidacion, Msg::VALIDATIONS);

          if($validation->fails())
              throw new ValidateRequestException(Utilerias::obtenerMensajesValidator($validation->getMessageBag()));

          $datos = $request->all();
          $datos['ip'] = $request->ip();
          $datos['userAgent'] = $request->userAgent();
          $result = ClienteServiceAction::registroCliente($datos);

          return response(ApiResponse::build(CodeResponse::EXITO,"Operación realizada correctamente.",$result));

      } catch (Throwable $e) {
          throw $e;
      }
  }

  /**
   * edita foto de cliente
   * @param Request $request
   * @return mixed
   */
  public function editarFoto(Request $request){
      try{

          $reglasValidacion = [
              'clienteId' => 'required|size:10',
              'foto' => 'required',
          ];

          $validation = Validator::make($request->all(),$reglasValidacion,Msg::VALIDATIONS);

          if($validation->fails())
              throw new Exception(Utilerias::obtenerMensajesValidator($validation->getMessageBag()));

          $datos = $request->all();
          ClienteServiceAction::editarFoto($datos);

          return response(ApiResponse::build(CodeResponse::EXITO, "Operación realizada correctamente."));

      }catch(Exception $e){
          return response(ApiResponse::build(CodeResponse::ERROR,$e->getMessage()));
      }
  }

  /**
   * edita perfil de cliente
   * @param Request $request
   * @return mixed
   */
  public function editarPassword(Request $request){
      try{
          $reglasValidacion = [
              'clienteId' => 'required|size:10',
              'password' => 'required',
              'passwordConfirm' => 'required',
          ];

          $validation = Validator::make($request->all(),$reglasValidacion,Msg::VALIDATIONS);

          if($validation->fails())
              throw new Exception(Utilerias::obtenerMensajesValidator($validation->getMessageBag()));

          $datos = $request->all();
          ClienteServiceAction::editarPassword($datos);

          return response(ApiResponse::build(CodeResponse::EXITO, "Operación realizada correctamente."));

      }catch(Exception $e){
          return response(ApiResponse::build(CodeResponse::ERROR,$e->getMessage()));
      }
  }
}
