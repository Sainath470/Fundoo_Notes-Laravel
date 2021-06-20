<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoteControllerTest extends TestCase
{
    public function test_IfGiven_LoginCrendentialsToken_AndData_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYyNDIyNjA2NCwiZXhwIjoxNjI0MjI5NjY0LCJuYmYiOjE2MjQyMjYwNjQsImp0aSI6ImJxcjFZVkdMbDJ3YjNnWkoiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.51FkkXH8sZ17pie2gC05yaWm0P16b3H1eU6jbP_OM6w',
        ])->json('POST', '/api/addNotes', [
            "title" => " hello title ",
            "description" => "php"
        ]);
        $response->assertStatus(200)->assertJson(['message' => 'Note created']);
    }

    public function test_IfGiven_WrongLoginCrendentialsToken_ShouldValidate_AndReturnErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJmlhdCI6MTYyNDIyNjA2NCwiZXhwIjoxNjImJxcjFZVkdMbDJ3YjNnWkoiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.51FkkXH8sZ17pie2gC05yaWm0P16b3H1eU6jbP_OM6w',
        ])->json('POST', '/api/addNotes', [
            "title" => " hello title ",
            "description" => "php"
        ]);
        $response->assertStatus(404)->assertJson(['message' => 'Invalid authorization token is invalid']);
    }


    public function test_IfGiven_LoginCrendentialsTokenForGetNotes_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYyNDIyNjA2NCwiZXhwIjoxNjI0MjI5NjY0LCJuYmYiOjE2MjQyMjYwNjQsImp0aSI6ImJxcjFZVkdMbDJ3YjNnWkoiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.51FkkXH8sZ17pie2gC05yaWm0P16b3H1eU6jbP_OM6w',
        ])->json('GET', '/api/getNotes', []);
        $response->assertStatus(200);
    }

    public function test_IfGiven_LoginCrendentialsTokenForGetNotes_ShouldValidate_AndReturnErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0ejoxNjI0MjI5NjY0LCJuYmYiOIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.51FkkXH8sZ17pie2gC05yaWm0P16b3H1eU6jbP_OM6w',
        ])->json('GET', '/api/getNotes', []);
        $response->assertStatus(201)->assertJson(['message' => "Invalid authorization token is invalid!"]);
    }

    public function test_IfGiven_UpdateDetailsAnd_LoginToken_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYyNDIyNzMzNywiZXhwIjoxNjI0MjMwOTM3LCJuYmYiOjE2MjQyMjczMzcsImp0aSI6IlJ1QnByRmtsV0FmYjlTOEUiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.EZiFDJfpg7HoJrU18HuZug_l0PhP3SEioN28oanO1l8',
        ])->json('POST', '/api/updateNote', [
            "id" => 1,
            "title" => "important",
            "description" => " laravel 87 ",
        ]);
        $response->assertStatus(200);
    }

    public function test_IfGiven_UpdateDetailsAnd_LoginToken_ShouldValidate_AndReturnErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYyNDIyNzMzNywiZXhwIjoxNjI0MjMwOTM3LCJuYmYiOjE2MjQyMjczMzcsImp0aSI6IlJ1QnByRmtsV0FmYjlTOEUiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.EZiFDJfpg7HoJrU18HuZug_l0PhP3SEioN28oanO1l8',
        ])->json('POST', '/api/updateNote', [
            "id" => 10,
            "title" => "important",
            "description" => " laravel 87 ",
        ]);
        $response->assertStatus(422);
    }

    public function test_IfGiven_DeleteDetailsAnd_LoginToken_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYyNDIyNzMzNywiZXhwIjoxNjI0MjMwOTM3LCJuYmYiOjE2MjQyMjczMzcsImp0aSI6IlJ1QnByRmtsV0FmYjlTOEUiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.EZiFDJfpg7HoJrU18HuZug_l0PhP3SEioN28oanO1l8',
        ])->json('POST', '/api/deleteNote', [
            "id" => 1,
        ]);
        $response->assertStatus(200)->assertJson(['message' => 'Note Deleted!']);
    }

    public function test_IfGiven_DeleteDetailsAnd_LoginToken_ShouldValidate_AndReturnErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYyNDIyNzMzNywiZXhwIjoxNjI0MjMwOTM3LCJuYmYiOjE2MjQyMjczMzcsImp0aSI6IlJ1QnByRmtsV0FmYjlTOEUiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.EZiFDJfpg7HoJrU18HuZug_l0PhP3SEioN28oanO1l8',
        ])->json('POST', '/api/deleteNote', [
            "id" => 1,
        ]);
        $response->assertStatus(422)->assertJson(['message' => 'Invalid note id']);
    }
}
