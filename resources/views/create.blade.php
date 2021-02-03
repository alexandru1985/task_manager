@extends('index')
@section('content-header')
    <h3 class="custom-h3">Add Task</h3>
@stop
@section('content')
<div class="content-height">
<form action="{{ route('tasks.store') }}" method="POST" novalidate>
@csrf 
    <div class="form-group row">
        <label for="client" class="col-sm-1 col-form-label"><b>Client</b></label>
        <div class="col-sm-3">
            <select class="custom-select" id="client_id" name="client_id">
            <option value="">Select Client</option>
            @foreach ($clients as $client)
            <option {{ (collect(old('client_id'))->contains($client->id)) ? 'selected':'' }}  value="{{$client->id}}">{{$client->name}}</option>
            @endforeach
            </select>
            @error('client_id')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
    </div>  
    <div class="form-group row">
        <label for="project" class="col-sm-1 col-form-label"><b>Project</b></label>
        <div class="col-sm-3">
            <select class="custom-select" id="project_id" name="project_id">
            <option value="">Select Project</option>
            @foreach ($projects as $key => $project)
            <option {{ (collect(old('project_id'))->contains($project->id)) ? 'selected':'' }} value="{{$project->id}}">{{$project->name}}</option>
            @endforeach
            </select>
            @error('project_id')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
    </div>  
    <div class="form-group row">
        <label for="task" class="col-sm-1 col-form-label"><b>Task</b></label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="name" placeholder="Task" name="name" value="{!! old('name') !!}">
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
    </div>  
    <div class="form-group row">
        <label for="users" class="col-sm-1 col-form-label"><b>Assigned Users</b></label>
        <div class="col-sm-3">
            <select class="custom-select" id="users" name="users[]" multiple="multiple" placeholder="Select User">
            @foreach ($users as $user)
            <option {{ (collect(old('users'))->contains($user->id)) ? 'selected':'' }} value="{{$user->id}}">{{$user->name}}</option>
            @endforeach
            </select>
            @error('users')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
    </div>  
    <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
<script>
$('#client_id').select2({

});
$('#project_id').select2({

});
$('#users').select2({
    placeholder: "Assigned Users",

});

function disabledAutoSortSelect(id_select2) {
    $("#" + id_select2).off('select2:select');
    $("#" + id_select2).on("select2:select", function (evt) {
        let element = evt.params.data.element;
        let $element = $(element);

        $element.detach();
        $(this).append($element);
        $(this).trigger("change");
    })
}

disabledAutoSortSelect('users');
</script>
@stop