<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function recordActivity($description)
    {
        // $this->activity()->create(['description' => $description]);
        // or
        $this->activity()->create(compact('description'));
    }


    // I did the same thing as the methods in the ProjectObserver just to experiment
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::created(function ($project) {
    //         $project->recordActivity('created');
    //     });

    //     static::updated(function ($project) {
    //         $project->recordActivity('updated');
    //     });
    // }
}
