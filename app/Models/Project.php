<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Team;
use App\Models\Task;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'team_id',
        'completion_percentage',
    ];

    protected $casts = [
        'completion_percentage' => 'decimal:2',
        'uuid' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function updateCompletionPercentage()
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks > 0) {
            $completedTasks = $this->tasks()->where('completed', true)->count();
            $this->completion_percentage = ($completedTasks / $totalTasks) * 100;
            $this->save();
        }
    }
}
