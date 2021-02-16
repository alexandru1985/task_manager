<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Clients;
use App\Models\Projects;
use App\Models\Tasks;
use App\Models\Users;
use App\Models\Roles;
use App\Models\UserTasks;

class ImportCSVController extends Controller {

    public function __construct() {
        $this->middleware('auth:api');
    }

    public function saveCSVtoDb(Request $request) {

        // Validate form
        $validator = $this->validateInputs($request);
        if ($validator) {
            return response()->json(['errors' => $validator->messages()], 422);
        } else {

            // Put CSV data in array
            $str = $request->file_csv;
            $initialData = array_map('str_getcsv', explode("\n", $str));
            $data = array_slice($initialData, 1);

            // Procesing CSV file and save data in database tables
            foreach ($data as $key => $rows) {
                if (is_string($rows[0])) {
                    $clients = Clients::firstOrNew([
                                'name' => trim($rows[0]),
                                'latitude' => trim($rows[1]),
                                'longitude' => trim($rows[2])
                            ]);
                    $clients->save();
                }
                if (is_string($rows[3])) {
                    $projects = Projects::firstOrNew([
                                'name' => trim($rows[3])
                    ]);
                    $projects->save();
                }
                if (is_string($rows[4])) {
                    $tasks = Tasks::firstOrNew([
                                'client_id' => $this->findClientId(trim($rows[0])),
                                'project_id' => $this->findProjectId(trim($rows[3])),
                                'name' => trim($rows[4])
                            ]);
                    $tasks->save();
                }
                if (is_string($rows[6])) {
                    $roles = explode(",", $rows[6]);
                    foreach ($roles as $role) {
                        $roles = Roles::firstOrNew([
                                    'name' => trim($role)
                                ]);
                        $roles->save();
                    }
                }
                if (is_string($rows[5])) {
                    $users = explode(",", $rows[5]);
                    $roles = explode(",", $rows[6]);
                    $projectId = $this->findProjectId(trim($rows[3]));
                    foreach ($users as $key => $user) {
                        $users = Users::firstOrNew([
                                    'role_id' => $this->findRoleId(trim($roles[$key])),
                                    'name' => trim($user)
                                ]);
                        $users->save();
                        $userTasks = UserTasks::firstOrNew([
                                    'user_id' => $this->findUserId(trim($user)),
                                    'task_id' => $this->findTaskId(trim($rows[4]), $projectId),
                                    'user_role' => Tasks::getUserRole($this->findUserId(trim($user)))
                                ]);
                        $userTasks->save();
                    }
                }
            }
            return response()->json(['message' => 'The csv file is saved to database'], 200);
        }
    }

    public function findUserId(string $userStr) {
        $user = Users::where('name', '=', $userStr)->first();
        return $user->id;
    }

    public function findTaskId(string $taskStr, int $projectId) {
        $task = Tasks::where('name', '=', $taskStr)->where('project_id', '=', $projectId)->first();
        return $task->id;
    }

    public function findRoleId(string $rolesStr) {
        $role = Roles::where('name', '=', $rolesStr)->first();
        return $role->id;
    }

    public function findClientId(string $clientStr) {
        $client = Clients::where('name', '=', $clientStr)->first();
        return $client->id;
    }

    public function findProjectId(string $projectStr) {
        $project = Projects::where('name', '=', $projectStr)->first();
        return $project->id;
    }

    public function validateInputs(Request $request) {
        $messages = [
            'file_csv.required' => 'The input file field is required',
        ];
        $rules = [
            'file_csv' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $validator;
        }
        $validator->after(function ($validator) use ($request) {
            if ($this->checkExtension($request->file_ext) == false) {
                $validator->errors()->add('file_type', 'The type of file must be csv');
            }
        });
        if ($validator->fails()) {
            return $validator;
        }
    }

    public function checkExtension(string $fileExt) {
        $valid = ['csv'];
        return in_array($fileExt, $valid) ? true : false;
    }

}
