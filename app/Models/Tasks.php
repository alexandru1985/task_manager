<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users;

class Tasks extends Model
{
    protected $fillable = ['client_id', 'project_id', 'name'];

    public static function validation() {
        return array(
            'rules' => [
                'client'=> 'required',
                'project'=> 'required',
                'name'=> 'required|min:5',
                'users'=> 'required' 
        ],
            'messages' => [
                'client.required' => 'The Client field is required',
                'project.required' => 'The Project field is required',
                'name.required' => 'The Task field is required',
                'name.min' => 'The Task field must be at least 5 characters',
                'users.required' => 'The Assigned Users field is required',
            ]
       );
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\Users', 'user_tasks', 'task_id', 'user_id')->withPivot('user_role')->orderBy('user_tasks.id','asc');
    }

    public function project()
    {
        return $this->hasOne('App\Models\Projects', 'id', 'project_id');
    }

    public function client()
    {
        return $this->hasOne('App\Models\Clients','id','client_id');
    }

    public static function getUserRole(int $userId){
        $user = Users::with('role')->whereIn('id', [$userId])->get();
        return $user[0]->role->name;
    }

}
