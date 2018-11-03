<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\DB;
use App\TypeCar;

class TypeCarController extends Controller
{
    public function show($id){

        $hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {
        
            if ($type_car = TypeCar::find($id)) {

                return response()->json(array(
                    'type_car' => $type_car,
                    'status' => 'success'
                ), 200);

            }else{
                return response()->json(array(
                    'status' => 'error',
                    'message' => 'El tipo de vehíuculo que intenta buscar, no existe, Intente con otra!'
                ), 400);
            }
        }
        
    }

    public function index(Request $request){

        $hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {

        	$type_cars = TypeCar::orderBy('name', 'DESC')->get();
        	return response()->json(array(
        		'type_cars' => $type_cars,
        		'status' => 'success'
        	), 200);
        }
    }
}
