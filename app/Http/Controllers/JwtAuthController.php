<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordReset;
use App\Notifications\ResetPasswordNotification;
use Exception;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthController extends Controller
{
    /**
     * login() function validates the credentials with the database if the credentials are correct and present in db
     * it will generate the token
     * along with status code and message. Else return error validation message
     * @param fails() Determine if the data fails the validation rules
     * @param make() requests data and returns validator
     * @param attempt checks the credentials and returns bool value
     */
    public function login(Request $request)
    {
        $req = FacadesValidator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);

        $email = $request->get('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['status' => 401, 'message' => "Invalid credentials! email doesn't exists"]);
        }

        if (!$token = auth()->attempt($req->validated())) {
            return response()->json(['status' => 401, 'message' => 'Unauthorized']);
        }

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
     * register function validates the credentials and if correct it will store in the database, if not gives error message
     * User is the model in which the database is given
     *
     * @return \Illuminate\Http\JsonResponse
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

        $email = $request->get('email');

        $userEmail = User::where('email', $email)->first();
        if ($userEmail) {
            return response()->json(['status' => 201, 'message' => "This email already exists...."]);
        }

        if ($req2->fails()) {
            return response()->json(['status' => 400, 'message' => "Password doesn't match"]);
        }

        $user = User::create(array_merge(
            $req->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'status' => 200,
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
        try{
        auth()->logout();
        }catch(Exception $e){
            return response()->json(['status' => 201, 'message' => 'Token is invalid']);
        }
        return response()->json(['status' => 200, 'message' => 'User logged out']);
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
     * This function is used to send the forgot password link to the respective email
     * @param [string] email
     * or
     * @return link or error message
     */
    public function forgotpassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['status' => 401, 'message' => "we can't find a user with that email address."]);
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
        return response()->json(['status' => 200, 'message' => 'we have emailed your password reset link to respective mail']);
    }

    /**
     * function to reset the password to the respective user id based on email and bearer token
     * @param [new_password] takes new password 
     * @param [confirm_password] takes password same as new_password
     * @param bearerToken() token passed through authorization header
     * 
     * @return success message or error based on validation
     */
    public function resetPassword(Request $request)
    {
        $validate = FacadesValidator::make($request->all(), [
            'new_password' => 'min:6|required|',
            'confirm_password' => 'required|same:new_password'
        ]);

        if ($validate->fails()) {
            return response()->json(['status' => 201, 'message' => "Password doesn't match"]);
        }

        $passwordReset = PasswordReset::where([
            ['token', $request->bearerToken()]
        ])->first();

        if (!$passwordReset) {
            return response()->json(['status' => 201, 'message' => 'This token is invalid']);
        }
        $user = User::where('email', $passwordReset->email)->first();

        if (!$user) {
            return response()->json(['status' => 200, 'message' => "we can't find the user with that e-mail address"]);
        } else {
            $user->password = bcrypt($request->new_password);
            $user->save();
            $passwordReset->delete();
            return response()->json(['status' => 200, 'message' => 'Password reset successfull!']);
        }
    }
}
