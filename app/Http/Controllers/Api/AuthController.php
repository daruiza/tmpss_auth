<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Carbon;
// use Carbon\Carbon;

class AuthController extends Controller
{
    private $name = 'name';
    private $email = 'email';
    private $password = 'password';
    
     /**
     * @OA\Post(
     *      path="/auth/login",
     *      operationId="getToken",
     *      tags={"Auth"},
     *      summary="Get User Token",
     *      description="Return Token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Login")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      )
     *     )
     */
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

            $user = $request->user();
            $tokenResult = $user->createToken(env('APP_NAME'));
            // $token = $tokenResult->token;
            // $token->expires_at = Carbon::now()->addDays(1);
            // $token->save();

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
                'error' => $e->getMessage()],
                403);
        }

        return response()->json([
            'data'=>'login',
            '$request'=>$request,
            'user'=>Auth::user(),
            'check'=>Auth::check(),
            '$user'=> $request->user(),
            // 'access_token' => $tokenResult->accessToken,
            // 'token_type'   => 'Bearer',
            // 'expires_at'   => Carbon::parse(
            //     $tokenResult->token->expires_at
            // )->toDateTimeString(),
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
