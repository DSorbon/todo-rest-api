<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTask extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'title', 'description'];

    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}