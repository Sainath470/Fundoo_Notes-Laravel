<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordReset;
use App\Notifications\ResetPasswordNotification;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Tymon\JWTAuth\Facades\JWTAuth;

/** 
 * 
 *
 * 
 * @OA\SecurityScheme(
 *      securityScheme="bearer_token",
 *      type="http",
 *      scheme="bearer"
 * )
 */

class JwtAuthController extends Controller
{

    /**
     * @OA\Post(
     ** path="/api/auth/login",
     *   tags={"Login"},
     *   summary="Login",
     *   operationId="login",
     *
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $req = FacadesValidator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|
            min:5',
        ]);

        $email = $request->get('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            Log::channel('mydailylogs')->alert( "Invalid credentials! email doesn't exists");
            return response()->json(['status' => 401, 'message' => "Invalid credentials! email doesn't exists"], 401);
        }
        if (!$token = auth()->attempt($req->validated())) {
            Log::channel('mydailylogs')->critical( 'Unauthenticated');
            return response()->json(['status' => 401, 'message' => 'Unauthenticated'], 401);
        }
        Log::channel('mydailylogs')->info('Login request:'.json_encode($request->all()));
        return $this->generateToken($token);
    }

    /**
     * generates token 
     * @creates a streamed response
     */
    protected function generateToken($token)
    {
        return response()->json([
            'status' => 200,
            'message' => 'succesfully logged in',
            'access_token' => $token
        ]);
    }

    /**
     * @OA\Post(
     ** path="/api/auth/register",
     *   tags={"Register"},
     *   summary="Register",
     *   operationId="register",
     *
     *  @OA\Parameter(
     *      name="firstName",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="lastName",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="password_confirmation",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $req = FacadesValidator::make($request->all(), [
            'firstName' => 'required|string|between:2,100',
            'lastName' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:user_registration'
        ]);

        $req2 = FacadesValidator::make($request->all(), [
            'password' => 'required|required_with:password_confirmation|min:3',
            'password_confirmation' => 'required|same:password'
        ]);

        $user = new User();
        $user->firstName = $request->input('firstName');
        $user->lastName = $request->input('lastName');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $email = $request->get('email');

        $userEmail = User::where('email', $email)->first();
        if ($userEmail) {
            Log::channel('mydailylogs')->warning("This email already exists....");
            return response()->json(['status' => 201, 'message' => "This email already exists...."], 201);
        }
        if ($req2->fails()) {
            Log::channel('mydailylogs')->warning("Password doesn't match");
            return response()->json(['status' => 400, 'message' => "Password doesn't match"], 400);
        }
        $user->save();
        Log::channel('mydailylogs')->info('Register request success:'.json_encode($request->all()));
        return response()->json([
            'status' => 201,
            'message' => 'User succesfully registered!'
        ]);
    }

    /**
     * Signout function is used to log the user out of application
     * @param logout() it logs the user out of the application
     * 
     * @return void
     */
    public function signout()
    {
        try {
            auth()->logout();
        } catch (Exception $e) {
            Log::channel('mydailylogs')->error("Token is invalid");
            return response()->json(['status' => 201, 'message' => 'Token is invalid'], 201);
        }
        Log::channel('mydailylogs')->info('User logged out');
        return response()->json(['status' => 200, 'message' => 'User logged out'], 200);
    }

    /**
     * refresh the token
     * @param refresh() refresh an instance on the given target and method
     */
    public function refresh()
    {
        return $this->generateToken(auth()->refresh());
    }

    /**
     * User function gets the user from the database
     */
    public function user()
    {
        return response()->json(auth()->user());
    }

    /**
     * @OA\Post(
     ** path="/api/auth/forgotPassword",
     *   tags={"Forgot Password"},
     *   summary="Forgot Password",
     *   operationId="forgotPassword",
     * 
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            Log::channel('mydailylogs')->error("email not found");
            return response()->json(['status' => 401, 'message' => "we can't find a user with that email address."], 401);
        }
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],

            [
                'email' => $user->email,
                'token' => JWTAuth::fromUser($user)
            ]
        );
        if ($user && $passwordReset) {
            $user->notify(new ResetPasswordNotification($passwordReset->token));
        }
        Log::channel('mydailylogs')->info('reset password request sent:'.json_encode($request->all()));
        return response()->json(['status' => 200, 'message' => 'we have emailed your password reset link to respective mail']);
    }

    /**
     * @OA\Post(
     ** path="/api/auth/resetPassword",
     *   tags={"Reset Password"},
     *   summary ="Reset Password",
     *   operationId="resetPassword",
     *
     *      @OA\Parameter(
     *      name="password_confirmation",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {
        $validate = FacadesValidator::make($request->all(), [
            'new_password' => 'min:6|required|',
            'confirm_password' => 'required|same:new_password'
        ]);

        if ($validate->fails()) {
            Log::channel('mydailylogs')->error("password doesn't match");
            return response()->json(['status' => 201, 'message' => "Password doesn't match"]);
        }
        $passwordReset = PasswordReset::where([
            ['token', $request->bearerToken()]
        ])->first();

        if (!$passwordReset) {
            Log::channel('mydailylogs')->critical('This token is invalid');
            return response()->json(['status' => 201, 'message' => 'This token is invalid'], 201);
        }
        $user = User::where('email', $passwordReset->email)->first();

        if (!$user) {
            Log::channel('mydailylogs')->warning("Email not found");
            return response()->json(['status' => 201, 'message' => "we can't find the user with that e-mail address"], 201);
        } else {
            $user->password = bcrypt($request->new_password);
            $user->save();
            $passwordReset->delete();
            Log::channel('mydailylogs')->info('Password reset successfull!');
            return response()->json(['status' => 200, 'message' => 'Password reset successfull!']);
        }
    }
}
