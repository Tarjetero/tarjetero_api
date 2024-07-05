<?php

namespace App\Http\Controllers;

use Exception;
use App\Helpers\ApiResponse;
use App\Helpers\CodeResponse;
use Illuminate\Http\Request;

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
      return response(ApiResponse::build(CodeResponse::EXITO, "Sesión correcta.", true));
    } catch (Exception $e) {
      return response(ApiResponse::build(CodeResponse::ERROR, $e->getMessage()));
    }
  }
}
