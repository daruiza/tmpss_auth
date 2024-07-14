<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
// use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Validation\ValidationException;

use GuzzleHttp;

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
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addDays(1);
            $token->save();

            // $response = Http::post(env('APP_URL') . '/oauth/token', [
            //     'grant_type' => 'password',
            //     'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
            //     'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
            //     'username' => $request->email,
            //     'password' => $request->password,
            //     'scope' => '',
            // ]);

            
            //$response = Http::get('http://host.docker.internal:8001/api/hello');
            //response = Http::get('http://0.0.0.0:8000/api/hello');
            //$response = Http::get('http://127.0.0.1:8001/api/hello');
            //$response = Http::get('http://192.168.58.100:8001/api/hello');
            //$response = Http::get('http://172.18.0.3:8000/api/hello');
            //$response = Http::get('http://localhost:8001/api/hello');
            //$user['token'] = $response->json();


            #$client = new GuzzleHttp\Client();
            #$res = $client->request('GET', 'http://api.local:8000/api/hello');
            #$user['Guzz']=$res;

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
            'data' => [
                'access_token' => $tokenResult->accessToken,
                'token_type'   => 'Bearer',
                'expires_at'   => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
            ],
            'message' => 'Usuario logueado con éxito!'
        ]);
    }

    public function logout(Request $request){}
    

    /**
     * @OA\Get(
     *      path="/auth/user",
     *      operationId="getUser",
     *      tags={"Auth"},
     *      summary="Get User Auth",
     *      description="Return User",
     *      security={ {"bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */

    public function user(Request $request){
        return response()->json([
            'data'=>'login',
            'user'=>Auth::user(),
            'check'=>Auth::check()
        ]);
    }
    
}
