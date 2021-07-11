<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['tasks', 'owner'];
    public $old = [];

    public function path(): string
    {
        return '/projects/' . $this->id;
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function addTask(array $data)
    {
        return $this->tasks()->create($data);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function activity(): HasMany
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function recordActivity($description)
    {
        $this->activity()->create(
            [
                'description' => $description,
                'changes' => $this->activityChanges($description)
            ]
        );
    }

    protected function activityChanges($description)
    {
        if ($description == 'updated') {
            return [
                'before' => Arr::except(array_diff($this->old, $this->getAttributes()), 'updated_at'),
                'after' => Arr::except($this->getChanges(), 'updated_at')
            ];
        }

        return null;
    }
}
