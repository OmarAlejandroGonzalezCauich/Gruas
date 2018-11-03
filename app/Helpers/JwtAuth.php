<?php 

namespace App\Helpers;

use Firebase\JWT\JWT; 
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth
{
	public $key; 

	public function __construct(){
		$this->key = '1Q2w3e4r5t6y7u8i9o0p';
	}

	public function signup($email, $password, $getToken = null){

		$user = User::where(
			array(
				'email' => $email, 
				'password' => $password
			))->first();

		$signup = false;

		if (is_object($user)) {
			$signup = true;
		}

		if ($signup) {
			//Generar token y devolverlo
			$token = array(
				'sub' => $user->id,
				'name' => $user->name, 
				'display_name' => $user->display_name,
				'email' => $user->email,
				'vat' => $user->vat,
				'mobile' => $user->mobile, 
				'active' => $user->active,
				//Cuando se creo el token
				'iat' => time(),
				// Cada cuando expira el token
				'exp' => time() + (7 * 24 * 60 * 60)
			);


			$jwt = JWT::encode($token, $this->key, 'HS256');
			$decoded = JWT::decode($jwt, $this->key, array('HS256'));

			if(is_null($getToken)){
				return $jwt;
			}else{
				return $decoded;
			}

		}else{
			return array(
    			'status' => 'error',
    			'code' => 400,
    			'message' => 'Los datos proporcionados son incorrectos, intente de nuevo!'
    		);
		}
	}

	public function checkToken($jwt, $getIdentity = false){
		$auth = false; 

		try{
			$decoded = JWT::decode($jwt, $this->key, array('HS256'));
		}catch(\UnexpectedValueException $e){
			$auth = false;
		}catch(\DomainException $e){
			$auth = false;
		}

		if (isset($decoded) && is_object($decoded) && isset($decoded->sub)) {
			$auth = true;
		}else{
			$auth = false;
		}

		if ($getIdentity) {
			return $decoded;
		}

		return $auth;
	}
}