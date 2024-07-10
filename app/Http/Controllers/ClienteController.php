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
                throw new Exception(Utilerias::obtenerMensajesValidator($validation->getMessageBag()));

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
     * edita un cliente
     * @param Request $request
     * @return mixed
     */
    public function editarCliente(Request $request){
        try{

            $reglasValidacion = [
                'clienteId'                => 'required|size:10',
                'nombreComercial'          => 'required',
            ];

            $validation = Validator::make($request->all(),$reglasValidacion,Msg::VALIDATIONS);

            if($validation->fails())
                throw new Exception(Utilerias::obtenerMensajesValidator($validation->getMessageBag()));

            $datos = $request->all();
            ClienteServiceAction::editarCliente($datos);

            return response(ApiResponse::build(CodeResponse::EXITO,"Operación realizada correctamente."));

        }catch(Exception $e){
            return response(ApiResponse::build(CodeResponse::ERROR,$e->getMessage()));
        }
    }

    /**
     * elimina un cliente
     * @param Request $request
     * @return mixed
     */
    public function eliminarCliente(Request $request){
        try{

            $reglasValidacion = [
                'clienteId'                => 'required|size:10',
                'motivoEliminar'          => 'required',
            ];

            $validation = Validator::make($request->all(),$reglasValidacion,Msg::VALIDATIONS);

            if($validation->fails())
                throw new Exception(Utilerias::obtenerMensajesValidator($validation->getMessageBag()));

            $datos = $request->all();
            ClienteServiceAction::eliminarCliente($datos);

            return response(ApiResponse::build(CodigosRes::EXITO,"Operación realizada correctamente."));

        }catch(Exception $e){
            return response(ApiResponse::build(CodigosRes::ERROR,$e->getMessage()));
        }
    }

    /**
     * Obtiene datos detalle
     * @param Request $request
     * @return mixed
     */
    public function obtenerDetalle(Request $request){
        try{

            $reglasValidacion = [
                'clienteId'          => 'required|size:10',
            ];

            $validation = Validator::make($request->all(),$reglasValidacion,Msg::VALIDATIONS);

            if($validation->fails())
                throw new Exception(Utilerias::obtenerMensajesValidator($validation->getMessageBag()));

            $clienteId = $request->clienteId;
            $result = ClienteServiceData::obtenerDetalle($clienteId);

            return response(ApiResponse::build(CodigosRes::EXITO,"Se obtuvo detalle correctamente.",$result));

        }catch(Exception $e){
            return response(ApiResponse::build(CodigosRes::ERROR,$e->getMessage()));
        }
    }

    /**
     * edita datos bancarios clientes
     * @param Request $request
     * @return mixed
     */
    public function agregarDatosBancarios(Request $request){
        try{

            $reglasValidacion = [
                'clienteId'                   => 'required|size:10',
                'nombreDatoBancario'          => 'required',
                'rfcBanco'                    => 'required',
                'nombreBanco'                 => 'required',
                'numeroCuenta'                => 'required',
            ];

            $validation = Validator::make($request->all(),$reglasValidacion,Msg::VALIDATIONS);

            if($validation->fails())
                throw new Exception(Utilerias::obtenerMensajesValidator($validation->getMessageBag()));

            $datos = $request->all();
            $result = ClienteServiceAction::agregarDatosBancarios($datos);

            return response(ApiResponse::build(CodigosRes::EXITO,"Se agrego dato bancario correctamente.", $result));

        }catch(Exception $e){
            return response(ApiResponse::build(CodigosRes::ERROR,$e->getMessage()));
        }
    }

}
