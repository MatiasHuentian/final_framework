<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Helpers\JwtAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    public function index(){
        $products = Product::get();
        $response = array(
            'status' => 'success',
            'code' => 200,
            'message' => 'Producto guardado exitosamente',
            'data' => $products
        );
        return response()->json($response,200);
    }

    public function store(Request $request){
        $hash = $request->header('Authorization',null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken){ 
            //Recibimos datos por Post
            $json = $request->input('json', null);
            $params = json_decode($json); //a Objeto
            $params_array = json_decode($json, true); // a Arreglo

            $validate = Validator::make($params_array,[
                'cod'           => 'required',
                'name'          => 'required|string|max:100',
                'category_id'   => 'required|numeric',
                'office_id'     => 'required|numeric',
                'description'   => 'required',
                'stock'         => 'required|numeric',
                'sell_price'    => 'required|numeric',
            ]);

            if($validate->fails()){
                $response = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => $validate->errors()
                );
            }else{
                //crear product
                $product = new Product();
                    // $product->user_id = $user->sub;
                    $product->cod           = $params->cod;
                    $product->name          = $params->name;
                    $product->category_id   = $params->category_id;
                    $product->office_id     = $params->office_id;
                    $product->description   = $params->description;
                    $product->stock         = $params->stock;
                    $product->sell_price    = $params->sell_price;
                $product->save();
                $response = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Producto guardado exitosamente',
                    'data' => $product
                );
            }
        }else{
            $response = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Login Incorrecto'
            );
        }
        return response()->json($response,200);
    }

    public function show( Request $request , $product_id ){
        $hash = $request->header('Authorization',null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken){ 
            //Recibimos datos por Post
            $product = Product::where('id' , '=' , $product_id)->first();
            // $product = Product::finOrFail($product_id);
            if( is_null($product) ){
                $response = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => "No se ha encontrado el producto especificado",
                );
            }else{
                $response = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Producto encontrado',
                    'data' => $product
                );
            }
        }else{
            $response = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Login Incorrecto'
            );
        }
        return response()->json($response,200);
    }

    //Actualizar nombre, precio y descripción del producto. 
    public function update( Request $request , $product_id ){
        $hash = $request->header('Authorization',null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken){ 
            //Recibimos datos por Post
            $json = $request->input('json', null);
            $params = json_decode($json); //a Objeto
            $params_array = json_decode($json, true); // a Arreglo

            $validate = Validator::make($params_array,[
                'name'          => 'required|string|max:100',
                'description'   => 'required',
                'sell_price'    => 'required|numeric',
            ]);

            if($validate->fails()){
                $response = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => $validate->errors()
                );
            }else{
                //crear product
                $product = Product::find($product_id);
                    // $product->user_id = $user->sub;
                    $product->name          = $params->name;
                    $product->description   = $params->description;
                    $product->sell_price    = $params->sell_price;
                $product->save();
                $response = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Producto actualizado exitosamente',
                    'data' => $product
                );
            }
        }else{
            $response = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Login Incorrecto'
            );
        }
        return response()->json($response,200);
    }

    public function destroy( Request $request , $product_id ){
        $hash = $request->header('Authorization',null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken){ 
            //Recibimos datos por Post
            $product = Product::where('id' , '=' , $product_id)->first();
            // $product = Product::finOrFail($product_id);
            if( is_null($product) ){
                $response = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => "No se ha encontrado el producto especificado",
                );
            }else{
                $product->delete();
                $response = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Producto eliminado correctamente',
                    'data' => $product
                );
            }
        }else{
            $response = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Login Incorrecto'
            );
        }
        return response()->json($response,200);
    }
}
