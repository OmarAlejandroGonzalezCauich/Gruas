<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\DB;
use App\Color;

class ColorController extends Controller
{
    public function show($id, Request $request){

        $hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {
            if ($color = Color::find($id)) {

            return response()->json(array(
                'color' => $color,
                'status' => 'success'
            ), 200);

            }else{
                return response()->json(array(
                    'status' => 'error',
                    'message' => 'El color que intenta buscar, no existe, Intente con otra!'
                ), 400);
            }

        }
        
    }

    public function index(Request $request){

        $hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {

        	$colors = Color::orderBy('name', 'DESC')->get();
        	return response()->json(array(
        		'colors' => $colors,
        		'status' => 'success'
        	), 200);
        }

    }
}
