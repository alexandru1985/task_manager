<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller {

    public function __construct() {
        $this->middleware('auth:api');
    }

    public function getUserTasks() {
        $userTasks = DB::table('users')
                ->select('users.name', DB::raw('count(user_tasks.task_id) as tasks'))
                ->join('user_tasks', 'users.id', '=', 'user_tasks.user_id')
                ->groupBy('users.name')->orderBy('tasks', 'desc')
                ->get();
        return $userTasks;
    }

    public function getUserRoles() {
        $userRoles = DB::table('users')
                ->select('roles.name', DB::raw('count(users.role_id) as count'))
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->groupBy('roles.name')->orderBy('count', 'desc')
                ->get();
        return $userRoles;
    }

}
