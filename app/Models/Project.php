<?php

namespace App\Models;

use App\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;
    use RecordsActivity;

    protected $guarded = [];
    protected $with = ['tasks', 'owner'];

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

    public function invite(User $user)
    {
        $this->members()->attach($user);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }
}
