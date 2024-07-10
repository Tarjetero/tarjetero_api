<?php

namespace App\Repository\Data;

use App\Constantes\StatusGlobal;
use App\Constantes\Usuario;
use App\Helpers\Constantes;
use App\Repository\RH\UsuarioRH;
use App\Utilerias\TelefonoUtils;
use App\Utilerias\TextoUtils;
use Exception;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\DB;
use stdClass;

class ClienteRepoData implements AuthorizableContract, AuthenticatableContract
{
    use Authenticatable, Authorizable;

    /**
     * Metodo para obtener la información del cliente
     * @param string $clienteId
     * @throws Exception
     */
    public static function getClienteInfo(string $clienteId)
    {
        try {
            $cliente = DB::table('cliente AS cl')
                ->select(
                    'cl.cliente_id',
                    'cl.nombre',
                    'cl.apellidos',
                    'cl.email',
                    'cl.mes_nacimiento',
                    'cl.status',
                    //Cliente Perfil
                    'clp.pin',
                    'clp.usuario',
                    'clp.token_Sesion',
                    // Pais
                    'cls.fecha_fin AS fecha_limite_suscripcion',
                )
                ->join('clientes_perfiles AS clp', 'cl.cliente_id', 'clp.cliente_id')
                ->join('clientes_suscripciones AS cls', 'cl.cliente_id', 'cls.cliente_id')
                ->where('cl.cliente_id', $clienteId)->get()->first();

            return $cliente;
        } catch (Exception $e) {
            throw new Exception("Problema en consulta obtener info cliente: " . $e->getMessage());
        }
    }

    /**
     * Metodo para obtener la información del cliente
     * @param string $clienteId
     * @throws Exception
     */
    public static function validarEmail(string $email)
    {
        try {
            $cliente = DB::table('clientes AS c')
                ->select(
                    'c.cliente_id',
                    'c.nombre',
                    'c.apellidos',
                    'c.email',
                    'c.status'
                )
                ->where('c.status', Constantes::STATUS_ACTIVO)
                ->where('c.email', $email)
                ->get()->first();

            return $cliente;
        } catch (Exception $e) {
            throw new Exception("Problema en consulta obtener info cliente: " . $e->getMessage());
        }
    }

    /**
     * Metodo para obtener la información del cliente
     * @param string $clienteId
     * @throws Exception
     */
    public static function validarUsuario(string $usuario)
    {
        $cliente = DB::table('clientes AS c')
            ->select(
                'c.cliente_id',
                'c.nombre',
                'c.apellidos',
                'cp.usuario',
                'c.status'
            )
            ->join('clientes_perfiles AS cp', 'c.cliente_id', 'cp.cliente_id')
            ->where('c.status', Constantes::STATUS_ACTIVO)
            ->where('cp.usuario', $usuario)
            ->get()->first();

            return $cliente;
    }

    /**
     * Metodo para validar cliente
     * @param string $pin
     * @param string $email
     * @param string $password
     * @throws Exception
     */
    public static function validarSesionCliente(string $email, string $password)
    {
        $passwordSalt = TextoUtils::generarContrasenia($password);

        $cliente = DB::table('clientes AS c')
            ->select(
                'cp.cliente_perfil_id',
                'cp.cliente_id',
                'cp.pin',
                'cp.usuario',
                // Cliente
                'c.nombre',
                'c.apellidos',
                'c.fecha_nacimiento',
                'c.sexo',
            )
            ->join('clientes_perfil AS cp', 'cp.cliente_id', '=', 'c.cliente_id')
            ->where('c.email', $email)
            ->where('cp.password', $passwordSalt)
            ->where('c.status', Constantes::STATUS_ACTIVO)
            ->get()->first();

        return $cliente;
    }
}
