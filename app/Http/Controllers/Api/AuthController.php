<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $name = 'name';
    private $email = 'email';
    private $password = 'password';
    
    public function login(Request $request)
    {

        $rules = [
            $this->email    => 'required|string|max:128|email',
            $this->password => 'required|string',
        ];

        try {

            $credentials = request([$this->name, $this->email, $this->password]);
            $validator = Validator::make($request->all(), $rules);
            if (!Auth::attempt($credentials) || $validator->fails()) {
                throw (new ValidationException($validator));
            }

        } 
        catch (ValidationException $e) {
            return response()->json([
                'message' => 'Credenciales mal suministradas', 
                'error' => $e->validator->errors()->messages()],
                403);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Credenciales no autorizadas', 
                'error' => $e],
                403);
        }

        return response()->json([
            'data'=>'login',
            // '$request'=>$request,
            'user'=>Auth::user(),
            'check'=>Auth::check(),
            '$credentials'=>$credentials,
            '$validator'=>$validator,
            'attempt'=>Auth::attempt($credentials)
        ]);
    }

    public function logout(Request $request){}
    

    public function user(Request $request){
        return response()->json([
            'data'=>'login',
            'user'=>Auth::user(),
            'check'=>Auth::check()
        ]);
    }
    
}
