<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use App\Helpers\CodeResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Throwable;
use Exception;
use Psr\Log\LogLevel;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
  /**
   * A list of exception types with their corresponding custom log levels.
   *
   * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
   */
  protected $levels = [
    QueryException::class => LogLevel::CRITICAL,
    ValidationException::class => LogLevel::WARNING,
  ];

  /**
   * A list of the exception types that are not reported.
   *
   * @var array<int, class-string<\Throwable>>
   */
  protected $dontReport = [
    //
  ];

  /**
   * A list of the inputs that are never flashed to the session on validation exceptions.
   *
   * @var array<int, string>
   */
  protected $dontFlash = [
    'current_password',
    'password',
    'password_confirmation',
  ];

  /**
   * Register the exception handling callbacks for the application.
   *
   * @return void
   */
  public function register()
  {
    $this->reportable(function (QueryException $e) {
      // Puedes personalizar cómo registrar las excepciones de Query aquí
      Log::critical('SQL Error: ' . $e->getMessage());
    });

    $this->reportable(function (ValidationException $e) {
      // Puedes personalizar cómo registrar las excepciones de validación aquí
      Log::warning('Validation Error: ' . $e->getMessage());
    });

    $this->reportable(function (BusinessException $e) {
      // Puedes personalizar cómo registrar las excepciones de validación aquí
      Log::warning('Business Error: ' . $e->getMessage());
    });

    // Manejo general de excepciones
    $this->reportable(function (Throwable $e) {
      Log::error('General Error: ' . $e->getMessage());
    });
  }

  /**
   * Render an exception into an HTTP response.
   *
   * @param \Illuminate\Http\Request $request
   * @param \Throwable $e
   * @return \Illuminate\Http\Response
   */
  public function render($request, Throwable $e)
  {
    if ($e instanceof QueryException) {
      return response(ApiResponse::build(CodeResponse::ERROR, "Ocurrió un error en DB!."));
    }
    if ($e instanceof ValidationException) {
      return response(ApiResponse::build(CodeResponse::ERROR, "Validación fallo!"));
    }
    if ($e instanceof ValidateRequestException) {
      return response(ApiResponse::build(CodeResponse::ERROR, $e->getMessage()));
    }
    if ($e instanceof BusinessException) {
      return response(ApiResponse::build(CodeResponse::ERROR, $e->getMessage()));
    }
    if ($e instanceof Exception) {
      return response(ApiResponse::build(CodeResponse::ERROR, $e->getMessage()));
    }
    // Manejo de otras excepciones
    return parent::render($request, $e);
  }
}
