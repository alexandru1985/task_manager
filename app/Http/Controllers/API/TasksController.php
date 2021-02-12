<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Tasks;
use App\Models\Clients;
use App\Models\Projects;
use App\Models\Users;
use Illuminate\Support\Facades\Validator;

class TasksController extends Controller {

    public function __construct() {
        $this->middleware('auth:api');
    }

    public function index(Request $request) {
        $filterListUsers = $request->filterListUsers;
        $tasks = Tasks::with('users', 'project', 'client')->whereHas('users', function ($q) use ($filterListUsers) {
                    if (!empty($filterListUsers)) {
                        $q->whereIn('users.id', $filterListUsers);
                    }
                })->orderBy('tasks.id', 'desc');

        if ($request->page == 'all') {
            return $tasks->get();
        } else {
            return $tasks->paginate(7);
        }
    }

    public function store(Request $request) {
        $array = Tasks::validation();

        $validator = Validator::make($request->all(), $array['rules'], $array['messages']);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        } else {
            $tasks = new Tasks([
                'client_id' => $request->client['id'],
                'project_id' => $request->project['id'],
                'name' => $request->name]);
            $tasks->save();

            $pivot = [];
            foreach ($request->users as $user) {
                //collect all the ids
                $pivot[$user['id']] = [
                'user_id' => $user['id'],
                'task_id' => $tasks->id,
                'user_role' => Tasks::getUserRole($user['id'])];
            }

            $tasks->users()->attach($pivot);

            return response()->json(['message' => 'Task was created'], 201);
        }
    }

    public function update($id, Request $request) {
        $array = Tasks::validation();

        $validator = Validator::make($request->all(), $array['rules'], $array['messages']);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        } else {
            $tasks = Tasks::find($id);
            $tasks->update([
                'client_id' => $request->client['id'],
                'project_id' => $request->project['id'],
                'name' => $request->name
                ]);

            if (isset($request->users)) {
                $pivot = [];
                foreach ($request->users as $user) {
                    //collect all the ids
                    $pivot[$user['id']] = [
                        'user_id' => $user['id'],
                        'task_id' => $id,
                        'user_role' => Tasks::getUserRole($user['id']
                    )];
                }

                $tasks->users()->sync($pivot);
            }
            return response()->json('The task successfully updated');
        }
    }

    public function destroy($id) {
        $tasks = Tasks::findOrFail($id);
        if ($tasks->delete()) {
            $tasks->users()->detach();
            return response()->json(['message' => 'The task was deleted'], 200);
        }
    }

    public function getClients() {
        return $clients = Clients::all();
    }

    public function getProjects() {
        return $projects = Projects::all();
    }

    public function getUsers() {
        return $users = Users::where('role_id', '!=', null)->get(['id', 'name']);
    }

}
