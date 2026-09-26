<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'order_num',
        'due_date',
        'weight_percentage',
        'status',
        'max_score',
        'score',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'weight_percentage' => 'integer',
            'max_score' => 'integer',
            'score' => 'integer',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class)->orderBy('version', 'desc');
    }

    public function latestSubmission(): HasOne
    {
        return $this->hasOne(Submission::class)->latestOfMany();
    }

    public function isOverdue(): bool
    {
        return $this->due_date->endOfDay()->isPast() && $this->status !== 'approved';
    }

    public function canBeSubmitted(): bool
    {
        return in_array($this->status, ['pending', 'needs_revision']);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}
