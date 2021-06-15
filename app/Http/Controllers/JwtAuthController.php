<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordReset;
use App\Notifications\ResetPasswordNotification;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\password;
use Illuminate\Support\Facades\Validator as FacadesValidator;

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

        if ($req->fails()) {
            return response()->json($req->errors(), 422);
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
            'message' => 'succesfully login',
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
            'email' => 'required|string|email|max:100|unique:user_registration',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($req->fails()) {
            return response()->json(['status' => 400, 'message' => 'Invalid credentials']);
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
        auth()->logout();
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
        $validate = FacadesValidator::make($request->all(), [
            'email' => 'required|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['status' => 401, 'message' => "we can't find a user with that email address."]);
        }
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],

            [
                'email' => $user->email,
                'token' => Str::random(60)
            ]
        );
        if ($user && $passwordReset) {
            $user->notify(new ResetPasswordNotification($passwordReset->token));
        }
        return response()->json(['status' => 200, 'message' => 'we have emailed your password reset link to respective mail']);
    }


    /**
     * function to validate the token and new password and modify it in the database
     * @param [string] token
     * @param [string] new_password
     * @param [string] confirm_password
     *  
     * @return password reset succesfull message or error message
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
            ['token', $request->token]
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
