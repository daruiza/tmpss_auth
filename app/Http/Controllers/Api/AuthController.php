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
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    private $name = 'name';
    private $email = 'email';
    private $password = 'password';
    
     /**
     * @OA\Post(
     *      path="/auth/clientlogin",
     *      operationId="getToken",
     *      tags={"Auth"},
     *      summary="Get User Token",
     *      description="Return Token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ClientLogin")
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
    public function clientLogin(Request $request)
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
                'token' => $token,
                'token_type'   => 'Bearer',
                'expires_at'   => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
            ],
            'message' => 'Usuario logueado con éxito!'
        ]);
    }


    /**
     * @OA\Post(
     *      path="/auth/passwordlogin",
     *      operationId="getPasswordToken",
     *      tags={"Auth"},
     *      summary="Get Password Token",
     *      description="Return Token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/PasswordLogin")
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
    public function passwordLogin(Request $request){

        // $response = Http::post(env('APP_URL', 'http://127.0.0.1:8001') . '/oauth/token', [
        //     'grant_type' => 'password',
        //     'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
        //     'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
        //     'username' => $request->email,
        //     'password' => $request->password,
        //     'scope' => '',
        // ]);

        $response = Http::post( 'http://127.0.0.1:8000/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => '9c8f9c29-8c42-462e-a4f7-7724a176db4f​',
            'client_secret' => 'eWRRObiLhhabYKUBYzWLRqozVgyLixDcZSs9qzDW'            
        ]);
       
        
        //$response = Http::get('http://host.docker.internal:8001/api/hello');
        //response = Http::get('http://0.0.0.0:8000/api/hello');
        //$response = Http::get('http://127.0.0.1:8001/api/hello');
        //$response = Http::get('http://192.168.58.100:8001/api/hello');
        //$response = Http::get('http://172.18.0.3:8000/api/hello');
        //$response = Http::get('http://localhost:8001/api/hello');
        //$user['token'] = $response->json();

        // $response = Http::get('http://172.18.0.1:8001/api/hello');
        // $response = Http::get('http://localhost:8001/api/hello');
        // $client = new GuzzleHttp\Client();
        // $res = $client->request('GET', 'http://172.18.0.1:8001/api/hello');
        // $user['Guzz']=$res;

        // gateway.docker.internal
        // 192.168.200.131

        // $response = Http::get('http://127.0.0.1:8001/api/hello')->throw()->json();

        
        return response()->json([
            'data' => [ 
                '$response' => $response->json(),
            ],
            'message' => 'Usuario logueado con éxito!'
        ]);
    }    

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
            'data'=>[
                'authuser'=>Auth::user(),
                'requestUser'=>$request->user()
            ],
        ]);
    }

    /**
     * @OA\Get(
     *      path="/auth/clientlogout",
     *      operationId="getClientLogout",
     *      tags={"Auth"},
     *      summary="Get User Client LogOut",
     *      description="Return Boolean",
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

    public function clientLogout(Request $request){

        // $request->user()->tokens()->delete();
        $request->user()->token()->revoke();

        return response()->json([
            'success' => true,
            'statusCode' => 204,
            'message' => 'Logged out successfully.',
        ], 204);
    }
    
}
