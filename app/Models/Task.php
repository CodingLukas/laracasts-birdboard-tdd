<?php

namespace App\Models;

use App\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
    use RecordsActivity;

    protected $fillable = ['body', 'completed'];
    protected $touches = ['project'];
    protected $casts = [
        'completed' => 'boolean'
    ];
    protected static $recordableEvents = ['created', 'deleted'];

    public function complete()
    {
        $this->update(['completed' => true]);

        $this->recordActivity('completed_task');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function path(): string
    {
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);

        $this->recordActivity('incompleted_task');
    }
}
