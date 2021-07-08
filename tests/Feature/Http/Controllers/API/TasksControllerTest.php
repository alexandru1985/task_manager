<?php

namespace Tests\Feature\Http\Controllers\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Models\Users;
use App\Models\Tasks;
use App\Models\UserTasks;

class TasksControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     * 
     */
    
    public function test_the_tasks_index_page_is_rendered_properly()
    {
        $this->withoutExceptionHandling();
        // Create a user
        $user = factory('App\User')->create();
        $this->actingAs($user,'api');
        // Hit the /tasks page
        $response = $this->get('api/tasks');
        // Assert status 200
        $response->assertStatus(200);
        
    }
    public function test_users_can_create_tasks() {
        $this->withoutExceptionHandling();
        // Create a user
        $user = factory('App\User')->create();
        $this->actingAs($user,'api');
        // Hit the tasks url with post request
        $data = [
            'client' =>  [ 'id'=> 1],
            'project' => [ 'id'=> 1],
            'name' => 'Task1',
            "users" => [
                0 => [
                  "id" => 2,
                  "name" => "James"
                ],
                1 =>  [
                  "id" => 3,
                  "name" => "Alice"
                ]
              ]
        ];
        $response = $this->json('POST','api/tasks', $data);
        // Assert status 201
        $tasks = Tasks::with('users');
        $tasks->get();
        $response->assertStatus(201);
    }

    public function test_users_can_update_task() {
        // We want to create a user
        $this->withoutExceptionHandling();

        // We want to create a user
        $user = factory('App\User')->create();
        $this->actingAs($user,'api');
        $task = factory('App\Models\Tasks')->create();
  
        // We want to hit the tasks url with post request

        $data = [
            'client' =>  [ 'id'=> 1 ],
            'project' => [ 'id'=> 1 ],
            'name' => 'Task1',
            "users" => [
                0 => [
                  "id" => 2,
                  "name" => "James"
                ],
              ]
        ];
        $response = $this->json('PUT','api/tasks/'.$task->id, $data);
        $tasks = Tasks::all();
        // We want to redirect to the tasks page
        $response->assertStatus(200);
    }
}
