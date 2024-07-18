<?php

namespace App\Services\BO;

use App\Helpers\Constantes;
use App\Utilerias\Utilerias;

class TarjetaBO
{
  /**
   * Se arma objeto de tarjeta de cliente para insert de DB
   *
   * @param  array   $datos
   * @return mixed
   */
  public static function armarInsert(array $datos): array
  {
      $insert = [];

      $insert['tarjeta_id']           = Utilerias::generateId();
      $insert['cliente_id']           = $datos['clienteId'];
      $insert['titular']              = $datos['titular'];
      $insert['titulo']               = $datos['titulo'];
      $insert['color']                = $datos['color'];
      $insert['numero']               = $datos['numero'];
      $insert['marca_tarjeta_id']     = $datos['marcaTarjetaId'];
      $insert['tipo']                 = $datos['tipo'] ?? Constantes::TARJETA_TIPO_TARJETERO;
      $insert['ultimos_digitos']      = substr($datos['numero'], -4);;
      $insert['anio_expiracion']      = !empty($datos['anioExpiracion']) ? $datos['anioExpiracion'] : null;
      $insert['mes_expiracion']       = !empty($datos['mesExpiracion']) ? $datos['mesExpiracion'] : null;
      $insert['codigo_cvv']           = !empty($datos['codigoCvv']) ? $datos['codigoCvv'] : null;
      $insert['comentario']           = !empty($datos['comentario']) ? $datos['comentario'] : null;
      $insert['status']               = Constantes::STATUS_ACTIVO;
      $insert['registro_fecha']       = Utilerias::now();

      return $insert;
  }

  /**
   * Metodo para armar update tarjeta de cliente
   * @param array $datos
   * @param $status
   * @return array $update
   */
  public static function armarUpdate($datos){
      $update = [];

      $update['numero']               = $datos['numero'];
      $update['ultimos_digitos']      = substr($datos['numero'], -4);
      $update['titular']              = $datos['titular'];
      $update['titulo']               = $datos['titulo'];
      $update['color']                = $datos['color'];
      $update['codigo_cvv']           = $datos['codigoCvv'];
      $update['comentario']           = $datos['comentario'];
      $update['marca_tarjeta_id']     = $datos['marcaTarjetaId'];
      $update['mes_expiracion']       = $datos['mesExpiracion'];
      $update['anio_expiracion']      = $datos['anioExpiracion'];
      $update['actualizacion_fecha']  = Utilerias::now();

      return $update;
  }

  /**
   * Se arma objeto de historico de una tarjeta editada de cliente para insert de DB
   *
   * @param  array   $datos
   * @return mixed
   */
  public static function armarTarjetaHistorico($tarjeta): array
  {
      $insert = [];

      $insert['tarjeta_historico_id'] = Utilerias::generateId();
      $insert['tarjeta_id']           = $tarjeta->tarjeta_id;
      $insert['cliente_id']           = $tarjeta->cliente_id;
      $insert['titular']              = $tarjeta->titular;
      $insert['titulo']               = $tarjeta->titulo;
      $insert['color']                = $tarjeta->color;
      $insert['numero']               = $tarjeta->numero;
      $insert['marca_tarjeta_id']     = $tarjeta->marca_tarjeta_id;
      $insert['ultimos_digitos']      = $tarjeta->ultimos_digitos;
      $insert['anio_expiracion']      = $tarjeta->anio_expiracion;
      $insert['mes_expiracion']       = $tarjeta->mes_expiracion;
      $insert['codigo_cvv']           = $tarjeta->codigo_cvv;
      $insert['comentario']           = $tarjeta->comentario;
      $insert['registro_fecha']       = Utilerias::now();

      return $insert;
  }
}