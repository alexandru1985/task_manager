<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Tasks;
use App\Models\Users;
use App\Models\Clients;
use App\Models\Projects;


class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $tasks = Tasks::with('users','projects','clients')->get();
        $users = Users::with('roles')->get();
        if($tasks->isEmpty() || $users->isEmpty()) {
            return view('empty-data');
        }
        else {
            return view('tasks', compact('tasks', 'users'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Clients::all();
        $projects = Projects::all();
        $users = Users::all();
        return view('create', compact('clients', 'projects', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'client_id'=> 'required',
            'project_id'=> 'required',
            'name'=> 'required|min:5',
            'users'=> 'required' 
        ];
        $messages = [
            'client.required' => 'The Client field is required',
            'project_id.required' => 'The Project field is required',
            'name.required' => 'The Task field is required',
            'name.min' => 'The Task field must be at least 5 characters',
            'users.required' => 'The Assigned Users field is required',
        ];
    
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            $tasks = new Tasks([
                'client_id' => $request->client_id,
                'project_id' => $request->project_id,
                'name' => $request->name
            ]);
            $tasks->save();
            $selectedUsers = $request->users;   
            $tasks->users()->attach($selectedUsers);

            return redirect('tasks');
        }
    }
}
