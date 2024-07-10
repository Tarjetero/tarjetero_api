<?php

namespace App\Http\Controllers;

use Exception;
use App\Helpers\Msg;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Helpers\CodeResponse;
use App\Services\Data\AuthServiceData;
use App\Utilerias\TextoUtils;
use App\Services\Data\ClienteServiceData;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\AuthenticationException;

class AuthController extends Controller
{
  /**
   * Obtiener listado de clientes
   * @param Request $request
   * @return mixed
   */
  public function authenticate(Request $request)
  {
    try {
      $datos = $request->all();

      $reglasValidacion = [
        'email'     => 'required',
        'password'  => 'required|max:50',
      ];

      $validacion = Validator::make($datos, $reglasValidacion, Msg::VALIDATIONS);

      if ($validacion->fails())
        throw new Exception(TextoUtils::obtenerMensajesValidator($validacion->getMessageBag()));

      $ip = $request->ip();
      $userAGent = $request->userAgent();
      $authDatos = AuthServiceData::autenticar($request->email, $request->password, $ip, $userAGent);

      return response(ApiResponse::build(CodeResponse::EXITO, "Sesión correcta.", $authDatos));
    } catch (AuthenticationException $e) {
      return response(ApiResponse::build(CodeResponse::CREDENCIALES_INVALIDAS, $e->getMessage(), null));
    } catch (Exception $e) {
      return response(ApiResponse::build(CodeResponse::ERROR, $e->getMessage()));
    }
  }

  /**
   * Metodo para deslogear cliente
   * @param Request $request
   * @return ApiResponse
   * @throws Exception
   */
  public function logout(Request $request)
  {
      try {

          $datos = $request->all();

          AuthServiceData::logout($datos);

          return response(ApiResponse::build(CodeResponse::EXITO, 'Éxito', null));
      } catch (Exception $e) {
          return response(ApiResponse::build(CodeResponse::ERROR, $e->getMessage()));
      }
  }
}
