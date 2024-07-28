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

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Facades\Hash;

use App\Models\User;

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



    /**
     * @OA\Get(
     *      path="/auth/redirect/{driver}",
     *      operationId="getRedirectSocialite",
     *      tags={"Socialite"},
     *      summary="Redirect to Driver Socialite",
     *      description="Redirect to Driver",
     *      @OA\Parameter(
     *          name="driver",
     *          description="Socialite Driver",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
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
    public function redirect(Request $request, string $driver){
        return Socialite::driver($driver)->redirect();
    }


    /**
     * @OA\Get(
     *      path="/auth/callback/{driver}",
     *      operationId="getCallbackSocialite",
     *      tags={"Socialite"},
     *      summary="Get Tocken Laravel",
     *      description="Return Token",
     *       @OA\Parameter(
     *          name="driver",
     *          description="Socialite Driver",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
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

    public function callback(Request $request, string $driver) {
        
        $githubUser  = Socialite::driver($driver??'github')->user();

        $user = User::updateOrCreate([
            'github_id' => $githubUser->id,
        ], [
            'name' => $githubUser->name??$githubUser->nickname,
            'email' => $githubUser->email,
            'password' => Hash::make('0000'),
            'github_id' => $githubUser->id,
            'github_token' => $githubUser->token,
            'github_refresh_token' => $githubUser->refreshToken,
        ]);   

        //Auth::login($user);
        //$tokenResult = $user->createToken(env('APP_NAME'));
        //$token = $tokenResult->token;
        //$token->expires_at = Carbon::now()->addDays(1);
        //$token->save();

        return response()->json([
            'data'=>[
                '$request'=>$request,
                'user'=>$user,
                '$githubUser'=>$githubUser,
                '$getNickname'=>$githubUser->getNickname(),
                'github_id' => $githubUser->id,
                '$name'=>$githubUser->name??$githubUser->nickname,
                'email' => $githubUser->email,
                'password' => Hash::make('0000'),
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ],
        ]);
    }
    
}
