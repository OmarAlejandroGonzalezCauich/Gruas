<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\DB;
use App\Brand;

class BrandController extends Controller
{
    public function show($id, Request $request){

        $hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {
            if ($brand = Brand::find($id)) {

            return response()->json(array(
                'brand' => $brand,
                'status' => 'success'
            ), 200);

            }else{
                return response()->json(array(
                    'status' => 'error',
                    'message' => 'La marca que intenta buscar, no existe, Intente con otra!'
                ), 400);
            }

        }
    }

    public function index(Request $request){

        $hash = $request->header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth->checkToken($hash);

        if ($checkToken) {
            $brands = Brand::orderBy('name', 'DESC')->get();
            return response()->json(array(
                'brands' => $brands,
                'status' => 'success'
            ), 200);
        }
    }
}
