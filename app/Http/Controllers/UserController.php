<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\DB;
use App\User;

class UserController extends Controller
{
    public function index(Request $request){
    	$users = User::select('id', 'name', 'display_name', 'password', 'email', 'mobile', 'token_app', 'vat', 'active')->get();
    	return response()->json(array(
    		'users' => $users,
    		'status' => 'success'
    	), 200);
    }

    public function show($id){
        
        if ($user = User::select('id', 'name', 'display_name', 'password', 'email', 'mobile', 'token_app', 'vat', 'active')->where('id', '=', $id)->get()) {

            return response()->json(array(
                'user' => $user,
                'status' => 'success'
            ), 200);

        }else{
            return response()->json(array(
                'status' => 'error',
                'message' => 'El usuario que trata de buscar no existe, intente de nuevo'
            ), 400);
        }
        
    }

    public function login(Request $request){
    	$JwtAuth = new JwtAuth(); 
    	//Recibir post 
    	$json = $request->input('json', null);
    	$params = json_decode($json); 

    	$email = (!is_null($json) && isset($params->email)) ? $params->email : null;
    	$password = (!is_null($json) && isset($params->password)) ? $params->password : null;
    	$getToken = (!is_null($json) && isset($params->getToken)) ? $params->getToken : null;

    	// Cifrar la password
    	$pwd = hash('sha256', $password);
        
    	if (!is_null($email) && !is_null($password) && ($getToken == null || $getToken == 'false')){

    		$signup = $JwtAuth->signup($email, $pwd);
    		
    	}else if($getToken != null){

            $signup = $JwtAuth->signup($email, $pwd, $getToken);

        }else{
            $signup = array(
                'status' => 'error', 
                'message' => 'Envia tus datos por post'
            );
        }

        return response()->json($signup, 200);

    }

    public function store(Request $request){
    	$json = $request->input('json', null);
    	$params = json_decode($json);

        $name = (!is_null($json) && isset($params->name)) ? $params->name : null;
        $display_name = (!is_null($json) && isset($params->display_name)) ? $params->display_name : null;
        $email = strtolower((!is_null($json) && isset($params->email)) ? $params->email : null);
        $password = (!is_null($json) && isset($params->password)) ? $params->password : null;
        $mobile = (!is_null($json) && isset($params->mobile)) ? $params->mobile : null;
        $vat = (!is_null($json) && isset($params->vat)) ? $params->vat : null;
        $remember_token = mt_rand();
        $create_date = now();
        $write_date = now();
        $create_uid = '1';
        $write_uid = '1';
        $company_id = '1';
        $notify_email = 'always';
        $sale_warn = 'no-message';
        $picking_warn = 'no-message';
        $purchase_warn = 'no-message';
        $invoice_warn ='no-message';
        $active = 'true';
        $customer = 'true';
        $is_company = 'true';
        
        if (!is_null($name) && !is_null($display_name) && !is_null($email) && !is_null($password) && !is_null($mobile) &&  $name != "" && $display_name != "" && $email != "" && $password != "" && $mobile != "") {
        	
        	$pwd = hash('sha256', $password);
        	$token_app = hash('sha256', $remember_token);

        	$user = new User(); 
            $user->name = $name;
            $user->display_name = $display_name;
            $user->email = $email;
            $user->mobile = $mobile;
            $user->password = $pwd;
            $user->vat = $vat;
            $user->token_app = $token_app;
            $user->create_date = $create_date;
            $user->write_date = $write_date;
            $user->create_uid = $create_uid;
            $user->write_uid = $write_uid;
            $user->notify_email = $notify_email;
            $user->sale_warn = $sale_warn;
            $user->picking_warn = $picking_warn;
            $user->purchase_warn = $purchase_warn;
            $user->invoice_warn = $invoice_warn;
            $user->active = $active;
            $user->customer = $customer;
            $user->company_id = $company_id;
            $user->is_company = $is_company;

            $isset_user = User::where('email', '=', $email)->first();

    		if (@count($isset_user) == 0) {

    			$user->save();

    			$data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Se ha dado de alta en el sistema, favor de iniciar sesión'
                );

    		}else{

    			$data = array(
	    			'status' => 'error',
	    			'code' => 400,
	    			'message' => 'El correo proporcionado ya esta dado de alta en el sistema'
	    		);

    		}

        }else{
    		$data = array(
    			'status' => 'error',
    			'code' => 400,
    			'message' => 'Los siguientes campos son obligatorios: Nombre, Apellido, Correo electrónico, Contraseña y Teléfono'
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
            unset($params_array['sub']);
            unset($params_array['iat']);
            unset($params_array['exp']);
            unset($params_array['created_at']);

            $isset_user = User::where('id', '=', $id)->first();

            if (@count($isset_user) > 0) {

                $user = User::where('id', $id)->update($params_array);

                if(isset($params_array['password'])){
                    
                    $pwd = hash('sha256', $params_array['password']);

                    $user = User::where('id', $id)->update(array('password' => $pwd));
                } 

                $data = array(
                    'user' => $params,
                    'message' => 'Se han actualizado sus datos de manera correcta', 
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
}
