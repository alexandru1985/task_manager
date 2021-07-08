<?php

namespace Tests\Feature\Http\Controllers\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportCSVControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->withoutExceptionHandling();
        // Create a user
        $user = factory('App\User')->create();

        $this->actingAs($user,'api');
       
        // Save csv to db
        $data = [
            'file_csv' => 'Client,Client Latitude,Client Longitude,Project,Task,Assigned Users,User Roles Involved
            Universal Software,40.712728,-74.006015,Instant Online Data,Import Client Data,"James, Alice, Samantha","Developer, Tester, Project Manager"
            ',
            'file_ext' => 'csv'
        ];
        $response = $this->json('POST','api/save-csv-to-db', $data);

        // Assert status 201
        $response->assertStatus(201);

    }
}
