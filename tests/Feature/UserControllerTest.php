<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_IfGiven_UserCredentials_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])->json('POST', '/api/register', [
            "firstName" => "krish",
            "lastName" => "sai",
            "email" => "Krish@gmail.com",
            "password" => "Krishna@123",
            "password_confirmation" => "Krishna@123"
        ]);
        $response->assertStatus(200)->assertJson(['message' => 'User succesfully registered!']);
    }


    public function test_IfGiven_SameUserCredentials_ShouldValidate_AndReturnErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])->json('POST', '/api/register', [
            "firstName" => "mark",
            "lastName" => "bell",
            "email" => "samuel@gmail.com",
            "password" => "mark@123",
            "password_confirmation" => "mark@123"
        ]);
        $response->assertStatus(201)->assertJson(['message' => 'This email already exists....']);
    }

    public function test_ifGiven_LoginCredentials_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])->json('POST', '/api/login', [
            "email" => "samuel@gmail.com",
            "password" => "mark@123"
        ]);
        $response->assertStatus(200)->assertJson(['message' => 'succesfully logged in']);
    }

    public function test_ifGiven_WrongLoginCredentials_ShouldValidate_AndReturnUnauthorized()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])->json('POST', '/api/login', [
            "email" => "samuel@gmail.com",
            "password" => "ma@123"
        ]);
        $response->assertStatus(401)->assertJson(['message' => 'Unauthorized']);
    }

    public function test_ifGiven_Email_ForForgotPassword_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])->json('POST', '/api/forgotPassword', [
            "email" => "akulavenkatasainath1997@gmail.com"
        ]);
        $response->assertStatus(200)->assertJson(['message' => 'we have emailed your password reset link to respective mail']);
    }

    public function test_ifGiven_Email_ForForgotPassword_ShouldValidate_AndReturnErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])->json('POST', '/api/forgotPassword', [
            "email" => "akulavenkatasaina997@gmail.com"
        ]);
        $response->assertStatus(401)->assertJson(['message' => "we can't find a user with that email address."]);
    }

    public function test_ifGiven_Token_ForResetPassword_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2FwaVwvZm9yZ290UGFzc3dvcmQiLCJpYXQiOjE2MjQyMjUzMDQsImV4cCI6MTYyNDIyODkwNCwibmJmIjoxNjI0MjI1MzA0LCJqdGkiOiI2a2IxajNHYXJXMkRzMlpCIiwic3ViIjozLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.wha6jrnL-5UtRVTF7bxuqXnQqEcILsUnKBma9pY3dLc',
        ])->json('POST', '/api/resetPassword', [
            "new_password" => "venkata@12345",
            "confirm_password" => "venkata@12345"
        ]);
        $response->assertStatus(200)->assertJson(['message' => "Password reset successfull!"]);
    }

    public function test_ifGiven_Token_ForResetPassword_ShouldValidate_AndReturnErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ9.eyJpc3MiOiJod9yZ290UGFzc3dCI2a2IxajNOTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.wha6jrnL-5UtRVTF7bxuqXnQqEcILsUnKBma9pY3dLc',
        ])->json('POST', '/api/resetPassword', [
            "new_password" => "venkata@12345",
            "confirm_password" => "venkata@12345"
        ]);
        $response->assertStatus(201)->assertJson(['message' => "This token is invalid"]);
    }
}
