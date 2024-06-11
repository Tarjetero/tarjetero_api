<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\CodigosRes;
use App\Msg;
use App\Services\Actions\ClienteServiceAction;
use App\Services\Data\ClienteServiceData;
use App\Utilerias\Utilerias;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClienteController
{
    /**
     * Obtiener listado de clientes
     * @param Request $request
     * @return mixed
     */
    public function listar(Request $request){
        try{

            $filtros = $request->all();

            $clientes = ClienteServiceData::listar($filtros);

            return response(ApiResponse::build(CodigosRes::EXITO,"Datos listados correctamente.",$clientes));

        }catch(Exception $e){
            return response(ApiResponse::build(CodigosRes::ERROR,$e->getMessage()));
        }
    }

    /**
     * Agrega un nuevo cliente
     * @param Request $request
     * @return mixed
     */
    public function agregarCliente(Request $request){
        try{

            $reglasValidacion = [
                'nombreComercial'          => 'required',
            ];

            $validation = Validator::make($request->all(),$reglasValidacion,Msg::VALIDATIONS);

            if($validation->fails())
                throw new Exception(Utilerias::obtenerMensajesValidator($validation->getMessageBag()));

            $datos = $request->all();
            $result = ClienteServiceAction::agregarCliente($datos);

            return response(ApiResponse::build(CodigosRes::EXITO,"Operación realizada correctamente.",$result));

        }catch(Exception $e){
            return response(ApiResponse::build(CodigosRes::ERROR,$e->getMessage()));
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

            return response(ApiResponse::build(CodigosRes::EXITO,"Operación realizada correctamente."));

        }catch(Exception $e){
            return response(ApiResponse::build(CodigosRes::ERROR,$e->getMessage()));
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
