<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LabelControllerTest extends TestCase
{

    public function test_IfGiven_LoginCrendentialsToken_AndLabelData_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYyNDIyNjA2NCwiZXhwIjoxNjI0MjI5NjY0LCJuYmYiOjE2MjQyMjYwNjQsImp0aSI6ImJxcjFZVkdMbDJ3YjNnWkoiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.51FkkXH8sZ17pie2gC05yaWm0P16b3H1eU6jbP_OM6w',
        ])->json('POST', '/api/makeLabel', [
            "label_name" => " hello title "
        ]);
        $response->assertStatus(200)->assertJson(['message' => 'Label created']);
    }

    public function test_IfGiven_LoginCrendentialsToken_AndLabelData_ShouldValidate_AndReturnErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYyNDIyNjA2NCwiZXhwIjoxNjI0MjI5NjY0LCJuYmYiOjE2MjQyMjYwNjQsImp0aSI6ImJxcjFZVkdMbDJ3YjNnWkoiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.51FkkXH8sZ17pie2gC05yaWm0P16b3H1eU6jbP_OM6w',
        ])->json('POST', '/api/makeLabel', [
            "label_name" => " hello title "
        ]);
        $response->assertStatus(201)->assertJson(['message' => 'duplicate label name not allowed']);
    }

    public function test_IfGiven_LoginCrendentialsTokenForGetLabels_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYyNDIyNjA2NCwiZXhwIjoxNjI0MjI5NjY0LCJuYmYiOjE2MjQyMjYwNjQsImp0aSI6ImJxcjFZVkdMbDJ3YjNnWkoiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.51FkkXH8sZ17pie2gC05yaWm0P16b3H1eU6jbP_OM6w',
        ])->json('GET', '/api/getlabels', []);
        $response->assertStatus(200);
    }

    public function test_IfGiven_LoginCrendentialsTokenForGetLabels_ShouldValidate_AndReturnErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGcXhwIjoxNjI0MjI5NjY0LCJuYmYiOjE2MjQyMjYwNjQsImp0aSI6ImJxcjFZVkdMbDJ3YjNnWkoiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.51FkkXH8sZ17pie2gC05yaWm0P16b3H1eU6jbP_OM6w',
        ])->json('GET', '/api/getlabels', []);
        $response->assertStatus(201);
    }

    public function test_IfGiven_LoginCrendentialsTokenUpdateLabel_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYyNDIyNjA2NCwiZXhwIjoxNjI0MjI5NjY0LCJuYmYiOjE2MjQyMjYwNjQsImp0aSI6ImJxcjFZVkdMbDJ3YjNnWkoiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.51FkkXH8sZ17pie2gC05yaWm0P16b3H1eU6jbP_OM6w',
        ])->json('POST', '/api/editLabelname', [
            "id" => 4,
            "label_name" => "sai label"
        ]);
        $response->assertStatus(200)->assertJson(['message' => "label updated!"]);
    }

    public function test_IfGiven_LoginCrendentialsTokenUpdateLabel_ShouldValidate_AndReturnErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYyNDIyNjA2NCwiZXhwIjoxNjI0MjI5NjY0LCJuYmYiOjE2MjQyMjYwNjQsImp0aSI6ImJxcjFZVkdMbDJ3YjNnWkoiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.51FkkXH8sZ17pie2gC05yaWm0P16b3H1eU6jbP_OM6w',
        ])->json('POST', '/api/editLabelname', [
            "id" => 40,
            "label_name" => "sai label"
        ]);
        $response->assertStatus(201)->assertJson(['message' => "label id not available"]);
    }

    public function test_IfGiven_LoginCrendentialsTokenDeleteLabel_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYyNDIyNjA2NCwiZXhwIjoxNjI0MjI5NjY0LCJuYmYiOjE2MjQyMjYwNjQsImp0aSI6ImJxcjFZVkdMbDJ3YjNnWkoiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.51FkkXH8sZ17pie2gC05yaWm0P16b3H1eU6jbP_OM6w',
        ])->json('POST', '/api/deleteLabel', [
            "id" => 5
        ]);
        $response->assertStatus(200)->assertJson(['message' => "label Deleted!"]);
    }

    public function test_IfGiven_LoginCrendentialsTokenDeleteLabel_ShouldValidate_AndReturnErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYyNDIyNjA2NCwiZXhwIjoxNjI0MjI5NjY0LCJuYmYiOjE2MjQyMjYwNjQsImp0aSI6ImJxcjFZVkdMbDJ3YjNnWkoiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.51FkkXH8sZ17pie2gC05yaWm0P16b3H1eU6jbP_OM6w',
        ])->json('POST', '/api/deleteLabel', [
            "id" => 5
        ]);
        $response->assertStatus(200)->assertJson(['message' => "Invalid label id"]);
    }
}
