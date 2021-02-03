<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTasks extends Model
{
    protected $table = 'user_tasks';
    protected $fillable = ['user_id', 'task_id', 'user_role'];
}
