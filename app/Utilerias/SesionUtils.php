<?php

namespace App\Utilerias;

class SesionUtils
{
    /** 
     * Metodo para generar token de session
     * @return string
     */
    public static function generarTokenOpaco()
    {
        $numeroRandom = random_int(1, 10000000);
        $sha          = hash('sha256', (string) $numeroRandom);
        $token        = substr($sha, -15);
        $token        = strtoupper($token);
        return $token;
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
}
