<?php


namespace App\Helpers;


class Constantes
{
    const VERSION_FRONT = 'V20240620-1200';

    const STATUS_PENDIENTE  = 100;
    const STATUS_PARCIAL    = 101;
    const STATUS_ACTIVO     = 200;
    const STATUS_LEGACY     = 205;
    const STATUS_ELIMINADO  = 300;

    //Constantes de tipo de metodo de request
    const ES_POST = 'POST';
    const ES_GET  = 'GET';
    const ES_PUT  = 'PUT';

    const USUARIO_TTL = 57600;

    const SUSCRIPCION_PAGADA = 2;
    const SUSCRIPCION_NO_PAGADA = 1;

    const DIAS_SUSCRIPCION = 30;

    const TARJETA_TIPO_TARJETERO = 1;
    const TARJETA_TIPO_GASTOS = 2;

    const EDITAR_FOTO = 'foto';
    const EDITAR_PASSWORD = 'password';

    //Constantes para filtros de orden
    const REGISTRO_FECHA_ASC       = 'registro_fecha_asc';
    const REGISTRO_FECHA_DESC      = 'registro_fecha_desc';
    const FOLIO_ASC                = 'folio_asc';
    const FOLIO_DESC               = 'folio_desc';
    const NOMBRE_ASC               = 'nombre_asc';
    const NOMBRE_DESC              = 'nombre_desc';
}
