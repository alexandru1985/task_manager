@extends('index')
@section('content-header')
    <h1 class="custom-h1-title">Tasks</h1>
@stop
@section('content')
<a href="{{ route('tasks.create') }}" type="button" class="btn btn-primary">Add Task <i class="fa fa-plus"></i></a>
<table class="custom-table table table-striped">
    <thead>
    <tr>
        <th>Client</th>
        <th>Project</th>
        <th>Task</th>  
        <th>Assigned Users</th>
        <th>User Roles Involved</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($tasks as $task)
    <tr>
        <td>{{$task->clients->name}}</td>
        <td>{{$task->projects->name}}</td>
        <td>{{$task->name}}</td>
        <td>@php
              $users = [];
              $roles = [];
              foreach($task->users as $task_users) { 
                $users[] = $task_users->name; 
                $roles[] = \App\Models\Users::find($task_users->id)->roles->name;
              }
              echo implode(', ',$users);
            @endphp
        </td>
        <td>@php
            echo implode(', ',$roles);
            @endphp
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
@stop