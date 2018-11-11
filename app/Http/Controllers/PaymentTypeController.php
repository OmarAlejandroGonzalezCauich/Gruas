<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\DB;
use App\PaymentType;

class PaymentTypeController extends Controller
{
    public function show($id, Request $request){

        $hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {
            if ($PaymentType = PaymentType::find($id)) {

            return response()->json(array(
                'paymentType' => $PaymentType,
                'status' => 'success'
            ), 200);

            }else{
                return response()->json(array(
                    'status' => 'error',
                    'message' => 'El tipo de pago que intenta buscar, no existe, Intente con otra!'
                ), 400);
            }

        }
        
    }

    public function index(Request $request){

        $hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {

        	$paymentTypes = PaymentType::orderBy('name', 'DESC')->get();
        	return response()->json(array(
        		'paymentTypes' => $paymentTypes,
        		'status' => 'success'
        	), 200);
        }

    }
}
