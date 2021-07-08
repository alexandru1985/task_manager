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
use DB;

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

            return response()->json(['message' => 'Task created'], 201);
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
            return response()->json(['message' => 'Task updated'], 200);
        }
    }

    public function destroy($id, Request $request) {
        $tasks = Tasks::findOrFail($id);
        if ($tasks->delete()) {
            $tasks->users()->detach();
            if(!empty($request->filterListUsers)) {
                $totalTasks = DB::table('users')
                ->select('users.name', DB::raw('count(user_tasks.task_id) as tasks'))
                ->join('user_tasks', 'users.id', '=', 'user_tasks.user_id')->whereIn('user_tasks.user_id', $request->filterListUsers)
                ->count();
            }
            else {
                $totalTasks = DB::table('tasks')->count();
            }
            $paginate = $totalTasks / 7;
            return response()->json(['message' => 'Task deleted', 'paginate'=> $paginate], 200);
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
