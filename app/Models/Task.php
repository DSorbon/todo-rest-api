<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['user_id', 'name'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function subTasks()
    {
        return $this->hasMany(SubTask::class);
    }
}
