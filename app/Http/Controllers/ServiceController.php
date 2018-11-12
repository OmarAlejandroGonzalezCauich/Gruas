<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\DB;
use App\Service;

class ServiceController extends Controller
{
    public function servicesByUser(Request $request){
        $hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {

            $user = $JwtAuth->checkToken($hash, true);
            $user_id = $user->sub;

            if ($servicesByUser = Service::where('partner_id', '=', $user_id)->get()->load('user')->load('car')->load('payment')->load('service')) {
                
                return response()->json(array(
                    'services' => $servicesByUser,
                    'status' => 'success'
                ), 200);

            }else{
                return response()->json(array(
                    'status' => 'error',
                    'message' => 'Usted no cuenta con ningún servicio registrado!'
                ), 400);
            }

            
        }
    }

    public function show($id, Request $request){

        $hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {

            if ($service = Service::find($id)->load('user')->load('car')->load('payment')->load('service')) {

            return response()->json(array(
                'service' => $service,
                'status' => 'success'
            ), 200);

            }else{
                return response()->json(array(
                    'status' => 'error',
                    'message' => 'El vehículo que intenta buscar, no existe, Intente con otra!'
                ), 400);
            }

        }
        
    }

    public function store(Request $request){
    	$hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {
        	
        	$user = $JwtAuth->checkToken($hash, true);
        	$json = $request->input('json', null);

        	date_default_timezone_set('America/Cancun');
    		
    		$params = json_decode($json);

    		$create_uid = '8';
    		$create_date = now();
    		$write_date = now();
    		$write_uid = '8';
    		$name = 'No se que hace esto';
    		$fecha = date('Y-m-d');
    		$state = 'draft';
    		$currency_id = '34';
    		$company_id = '1';
    		$pricelist_id = '1';
    		$horainicio = date('Y-m-d').' '.date('H:i:s');
    		$creada_de_app = 'True';
    		$vehiculo_id = (!is_null($json) && isset($params->vehiculo_id)) ? $params->vehiculo_id : null;
    		$tiposervicio_id = (!is_null($json) && isset($params->tiposervicio_id)) ? $params->tiposervicio_id : null;
    		$tipopago_id = (!is_null($json) && isset($params->tipopago_id)) ? $params->tipopago_id : null;
            $partner_id = $user->sub;
            $solicito = $user->name.' '.$user->display_name;
            $telefono = $user->mobile;
            $seencuentra = (!is_null($json) && isset($params->seencuentra)) ? $params->seencuentra : null;
            $selleva = (!is_null($json) && isset($params->selleva)) ? $params->selleva : null;
            $operador_id = '1';//Checar este campo
            $grua_id = '1';//Checar este campo
            $tipogrua_id = '1';// checar este campo
            $tmestimadoarribo = 'En proceso...';// checar este campo

            if (!is_null($partner_id) && !is_null($vehiculo_id) && !is_null($tiposervicio_id) && !is_null($tipopago_id) && !is_null($seencuentra) && !is_null($selleva) &&  $partner_id != "" && $vehiculo_id != "" && $tiposervicio_id != "" && $tipopago_id != "" && $seencuentra != "" && $selleva != "") {

            	$service = new Service(); 
            	$service->create_uid = $create_uid;
            	$service->create_date = $create_date;
            	$service->write_date = $write_date;
            	$service->write_uid = $write_uid;
            	$service->name = $name;
            	$service->fecha = $fecha;
            	$service->state = $state;
            	$service->currency_id = $currency_id;
            	$service->company_id = $company_id;
            	$service->pricelist_id = $pricelist_id;
            	$service->horainicio = $horainicio;
            	$service->creada_de_app = $creada_de_app;
            	$service->vehiculo_id = $vehiculo_id;
            	$service->tiposervicio_id = $tiposervicio_id;
            	$service->tipopago_id = $tipopago_id;
            	$service->partner_id = $partner_id;
            	$service->solicito = $solicito;
            	$service->telefono = $telefono;
            	$service->seencuentra = $seencuentra;
            	$service->selleva = $selleva;
            	$service->operador_id = $operador_id;
            	$service->grua_id = $grua_id;
            	$service->tmestimadoarribo = $tmestimadoarribo;
            	$service->tipogrua_id = $tipogrua_id;

            	$service->save();

    			$data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Se ha dado de alta su solicitud!'
                );

            }else{
            	$data = array(
	    			'status' => 'error',
	    			'code' => 400,
	    			'message' => 'Todos los campos son obligatorios'
	    		);
            }

        }else{
        	$data = array(
                'message' => 'No cuentas con los permisos necesarios',
                'status' => 'error', 
                'code' => 300,
            );
        }
        return response()->json($data, 200);
    }

}
