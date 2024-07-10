<?php

namespace App\Services\Data;

use stdClass;
use Exception;
use Firebase\JWT\JWT;
use App\Helpers\Constantes;
use App\Utilerias\Utilerias;
use App\Utilerias\TextoUtils;
use App\Utilerias\SesionUtils;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Repository\Data\ClienteRepoData;
use App\Repository\Actions\AuthRepoAction;

use function Laravel\Prompts\password;

class AuthServiceData
{
    /**
     * Generamos un nuevo token.
     *
     * @param string $usuarioId
     * @return string
     * @throws Exception
     */
    protected static function jwt(string $clienteId, $ip, $userAgent)
    {
        $sub = Utilerias::generateId();
        $payload = [
            'iss' => "tarjetero-jwt", // Emisor del token
            'sub' => $sub, // Identificador del token
            'uid' => $clienteId, // Identificador del usuario
            'iat' => time(), // Hora de emision del token
            'exp' => strtotime('now +' . env('APP_JWT_EXP') . ' minutes'),
            'ip'  => $ip// Expiracion del token
        ];

        $token = JWT::encode($payload, env('APP_JWT_KEY'), 'HS512');

        return $token;
    }

    /**
     * Metodo para autenticar clientes
     * @param $filtros
     * @return array $clientes
     * @throws Exception
     */
    public static function autenticar($email, $password, $ip, $userAgent)
    {
        // Se busca usuario en DB
        $cliente = ClienteRepoData::validarSesionCliente($email, $password);

        if(empty($cliente))
            throw new Exception('No cuenta con el acceso a esta aplicación');

        $token    = self::jwt($cliente->cliente_id, $ip, $userAgent);
        $authData = clone self::armarAuthData($token, $cliente);

        //$request->bearer();

        AuthRepoAction::actualizarClienteSesion($cliente->cliente_id, [
                'ultimo_acceso' => Utilerias::now(),
                'token_sesion' => $token,
                'token_fecha' => Utilerias::now()
            ]
        );

        return $authData;
    }

    /**
     * @param $token
     * @param $usuario
     * @param $authCorporativo
     * @return stdClass
     * @throws Exception
     */
    private static function armarAuthData($token, $cliente)
    {
        // Se arma respuesta login
        $respuesta = new stdClass();
        $respuesta->token           = $token;
        $respuesta->pin             = $cliente->pin;
        $respuesta->clienteId       = $cliente->cliente_id;
        $respuesta->usuario         = $cliente->usuario;
        $respuesta->nombre          = $cliente->nombre;
        $respuesta->apellidos       = $cliente->apellidos;
        $respuesta->sexo            = $cliente->sexo;
        $respuesta->fechaNacimiento = $cliente->fecha_nacimiento;
        //$respuesta->zonaHoraria = $cliente->zona_horaria;

        return $respuesta;
    }

    /**
     * Metodo para deslogear cliente (logout)
     * @param array $datos
     * @return void
     * @throws Exception
     */
    public static function logout($datos)
    {
        $update = [];
        $update['token_sesion']         = null;
        $update['token_fecha']          = null;
        $update['actualizacion_fecha']  = Utilerias::now();
        // Se borra token de DB
        AuthRepoAction::actualizarClienteSesion($update, $datos['clienteId']);

        return null;
    }
}
