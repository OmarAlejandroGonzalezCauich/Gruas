<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\DB;
use App\TypeService;

class TypeServiceController extends Controller
{
    public function show($id, Request $request){

        $hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {
            if ($typeService = TypeService::find($id)) {

            return response()->json(array(
                'typeService' => $typeService,
                'status' => 'success'
            ), 200);

            }else{
                return response()->json(array(
                    'status' => 'error',
                    'message' => 'El tipo de servicios que intenta buscar, no existe, Intente con otra!'
                ), 400);
            }

        }
        
    }

    public function index(Request $request){

        $hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {

        	$typeServices = TypeService::orderBy('name', 'DESC')->get();
        	return response()->json(array(
        		'typeServices' => $typeServices,
        		'status' => 'success'
        	), 200);
        }

    }
}
