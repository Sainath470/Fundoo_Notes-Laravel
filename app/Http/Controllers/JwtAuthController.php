<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Validation\Rules\Password as RulesPassword;

class JwtAuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
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
     * Generate token
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
     * Sign up.
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
            return response()->json(['status' => 400, $req->errors()->toJson()]);
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
     * Sign out
     */
    public function signout()
    {
        auth()->logout();
        return response()->json(['status' => 200, 'message' => 'User logged out']);
    }

    // /**
    //  * Token refresh
    // */
    public function refresh()
    {
        return $this->generateToken(auth()->refresh());
    }

    /**
     * User
     */
    public function user()
    {
        return response()->json(auth()->user());
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return [
                'status' => __($status)
            ];
        }

        if ($status == Password::RESET_THROTTLED){
            return [
                'status' => __($status)
            ];
        }

        if($status == Password::INVALID_USER){
            return response()->json(['status' => 401, 'message' => 'This email does not exist']);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(30)
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'status' => 200,
                'message' => 'Password reset successfully'
            ]);
        }

        return response([
            'message' => __($status)
        ], 500);
    }
}
