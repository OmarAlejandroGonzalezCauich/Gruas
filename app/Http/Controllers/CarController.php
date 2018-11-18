<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\DB;
use App\Car;

class CarController extends Controller
{

    public function carsByUser(Request $request){
        $hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {

            $user = $JwtAuth->checkToken($hash, true);
            $user_id = $user->sub;

            if ($carsByUser = Car::where('partner_id', '=', $user_id)->get()->load('user')->load('brand')->load('typecar')->load('color')) {
                
                return response()->json(array(
                    'cars' => $carsByUser,
                    'status' => 'success'
                ), 200);
            }else{
                return response()->json(array(
                    'status' => 'error',
                    'message' => 'Usted no cuenta con ningún vehículo registrado!'
                ), 400);
            }

            
        }
    }

    public function show($id, Request $request){

        $hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {

            if ($car = Car::find($id)->load('user')->load('brand')->load('typecar')->load('color')) {

            return response()->json(array(
                'car' => $car,
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

    public function index(Request $request){

    	$hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {

        	$user = $JwtAuth->checkToken($hash, true);
            $user_id = $user->sub;

        	$cars = Car::all();
    		
    		return response()->json(array(
	    		'cars' => $cars,
	    		'status' => 'success'
	    	), 200);

        }

        return response()->json($data, 200);
    	
    }

    public function store(Request $request){
    	$hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {
        	
        	$user = $JwtAuth->checkToken($hash, true);
        	$json = $request->input('json', null);
    		
    		$params = json_decode($json);

            $partner_id = $user->sub;
            $anio = (!is_null($json) && isset($params->anio)) ? $params->anio : null;
        	$colorvehiculo_id = (!is_null($json) && isset($params->colorvehiculo_id)) ? $params->colorvehiculo_id : null;
        	$name = (!is_null($json) && isset($params->name)) ? $params->name : null;
        	$create_uid = '1';
        	$placas = (!is_null($json) && isset($params->placas)) ? $params->placas : null;
        	$clase = (!is_null($json) && isset($params->clase)) ? $params->clase : null;
        	$write_uid = '1';
        	$tipovehiculo_id = (!is_null($json) && isset($params->tipovehiculo_id)) ? $params->tipovehiculo_id : null;
        	$write_date = now();
        	$marca_id = (!is_null($json) && isset($params->marca_id)) ? $params->marca_id : null;
        	$create_date = now();
        	$noserie = (!is_null($json) && isset($params->noserie)) ? $params->noserie : null;

        	if (!is_null($partner_id) && !is_null($anio) && !is_null($name) && !is_null($placas) && !is_null($clase) && !is_null($colorvehiculo_id) && !is_null($tipovehiculo_id) &&  $partner_id != "" && $anio != "" && $name != "" && $placas != "" && $clase != "" && $colorvehiculo_id != "" && $tipovehiculo_id != "") {

        		$car = new Car(); 
            	$car->partner_id = $partner_id;
            	$car->anio = $anio;
            	$car->colorvehiculo_id = $colorvehiculo_id;
            	$car->name = $name;
            	$car->create_uid = $create_uid;
                $car->create_date = $create_date;
                $car->noserie = $noserie;
            	$car->placas = $placas;
            	$car->clase = $clase;
            	$car->write_date = $write_date;
                $car->write_uid = $write_uid;
            	$car->marca_id = $marca_id;
                $car->tipovehiculo_id = $tipovehiculo_id;

            	$car->save();

    			$data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Se ha dado de alta el vehículo!'
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

    public function update($id, Request $request){
        $hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {
            
            $json = $request->input('json', null);
            $params = json_decode($json);
            $params_array = json_decode($json, true); 

            // Validar datos 

            //Actualizar al usuario
            unset($params_array['id']);
            unset($params_array['created_uid']);
            unset($params_array['write_uid']);
            unset($params_array['create_date']);
            unset($params_array['write_date']);
            unset($params_array['brand']);
            unset($params_array['typecar']);
            unset($params_array['color']);
            unset($params_array['user']);
            unset($params_array['partner_id']);

            $isset_user = Car::where('id', '=', $id)->first();

            if (@count($isset_user) > 0) {

                $user = Car::where('id', $id)->update($params_array);

                if(isset($params_array['password'])){
                    
                    $pwd = hash('sha256', $params_array['password']);

                    $user = Car::where('id', $id)->update(array('password' => $pwd));
                } 

                $data = array(
                    'user' => $params,
                    'message' => 'Se han actualizado los datos de manera correcta', 
                    'status' => 'success',
                    'code' => 200
                );

            }else{

                $data = array(
                    'message' => 'El id Proporcionado no existe!',
                    'status' => 'error', 
                    'code' => 400,
                );

            }
        }else{
            $data = array(
                'message' => 'Login Incorrecto',
                'status' => 'error', 
                'code' => 300,
            );
        }
        return response()->json($data, 200);
    }

    public function destroy($id, Request $request){
        $hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {

            $car = Car::find($id);
            $car->delete();

            $data = array(
                'message' => 'Se ha eliminado el vehículo de manera correcta!',
                'status' => 'success', 
                'code' => 200,
            );

        }else{
            $data = array(
                'message' => 'Login Incorrecto',
                'status' => 'error', 
                'code' => 300,
            );
        }
        return response()->json($data, 200);
    }
}
