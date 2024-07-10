<?php

namespace App\Utilerias;

use Exception;
use Hidehalo\Nanoid\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;

class TextoUtils
{
    /**
     * Metodo para obtener mensajes de clase validator
     * @param MessageBag $excepciones
     * @return string $mensajes
     */
    public static function obtenerMensajesValidator(MessageBag $excepciones)
    {
        $mensajes = "";

        foreach ($excepciones->all() as $excepcion)
            $mensajes .= $excepcion . '<br>';

        return $mensajes;
    }

    /** 
     * Metodo para generar id
     * @return string
     */
    public static function generarId()
    {
        $nanoId   = new Client();
        $alphabet = "-0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        return $nanoId->formatedId($alphabet, 10);
    }

    /** 
     * Metodo para generar contrasenia
     * @param string $pass
     * @return string
     */
    public static function generarContrasenia(string $pass)
    {
        $salt = env('APP_SALT');
        return md5($pass . $salt);
    }

    /** 
     * Metodo para agregar log de error
     * @param Exception $e | Excepcion producida
     * @param string $clase | Clases y metodo del error
     */
    public static function agregarLogError(Exception $e, string $clase)
    {
        $p = $e->getPrevious();
        $codigo = $e->getCode();

        // Se valida que la excepcion no haya sido registrada y que provenga de clases locales
        if (empty($p) || $codigo != 300) {
            $lineaError   = $e->getLine();
            $mensajeError = $e->getMessage();
            Log::channel('errorlog')->error("{$clase}");
            Log::channel('errorlog')->error("Linea: {$lineaError}");
            Log::channel('errorlog')->error("Mensaje: {$mensajeError}");
            Log::channel('errorlog')->error("=================================================================================");
        }
    }

    /**
     * Método que retorna un string limpio de caracteres especiales
     * @param $cadena Texto a limpiar
     * @return false|string
     */
    public static function limpiarCadena($cadena)
    {
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby';
        $cadena = mb_convert_encoding($cadena,  'ISO-8859-1', 'UTF-8');
        $cadena = strtr($cadena, mb_convert_encoding($originales,  'ISO-8859-1', 'UTF-8'), $modificadas);
        return mb_convert_encoding($cadena, 'UTF-8', 'ISO-8859-1');
    }
}
