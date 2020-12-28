<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Project extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $old = [];

    // protected function serializeDate(DateTimeInterface $date)
    // {
    //     return $date->format('Y-m-d H:i:s');
    // }

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
        return $this->hasMany(Activity::class)->latest();
    }

    public function recordActivity($description)
    {
        $this->activity()->create([
            'description' => $description,
            'changes' =>  $this->activityChanges($description)
        ]);
    }

    protected function activityChanges($description)
    {
        if ($description !== 'updated') {
            return null;
        }
        return [
            'before' => Arr::except(array_diff($this->old, $this->getAttributes()), 'updated_at'),
            'after' => Arr::except($this->getChanges(), 'updated_at')
        ];
    }


    // I did the same thing as the methods in the ProjectObserver just to experiment. Creating an Observer is
    //easier and cleaner BUT MAKE SURE you include it inside AppServiceProvider booth() !!!
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
