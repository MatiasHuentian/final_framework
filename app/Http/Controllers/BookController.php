<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Models\Book;

class BookController extends Controller
{
    //Index
    public function index(Request $request){
        $hash = $request->header('Authorization',null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken){ 
            echo "Index Autenticado"; die();
        }else{
            echo "No Autenticado"; die();
        }
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

            $validate = \Validator::make($params_array,[
                'autor' => 'required',
                'title' => 'required',
                'price' => 'required',
                'status' => 'required'
            ]);

            if($validate->fails()){
                $response = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => $validate->errors()
                );
            }else{
                //obtengo token decodificado
                $user = $jwtAuth->checkToken($hash, true);

                //crear el libro
                $book = new Book();
                $book->user_id = $user->sub;
                $book->autor = $params->autor;
                $book->title = $params->title;
                $book->price = $params->price;
                $book->status = $params->status;

                $book->save();
                $response = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Libro guardado',
                    'data' => $book
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
