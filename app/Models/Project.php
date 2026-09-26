<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'academic_year',
        'status',
        'supervisor_id',
        'github_repository',
        'progress_percentage',
    ];

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')
                    ->withPivot('role_in_team')
                    ->withTimestamps();
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class)->orderBy('order_num');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class)->latest();
    }

    /**
     * Recalculate and update the overall project progress percentage
     */
    public function recalculateProgress(): void
    {
        $milestones = $this->milestones;
        if ($milestones->isEmpty()) {
            $this->update(['progress_percentage' => 0]);
            return;
        }

        $completedWeight = $milestones->where('status', 'approved')->sum('weight_percentage');
        $this->update(['progress_percentage' => min(100, (int) $completedWeight)]);
    }

    public function isMember(int $userId): bool
    {
        return $this->members()->where('users.id', $userId)->exists();
    }

    public function isSupervisor(int $userId): bool
    {
        return $this->supervisor_id === $userId;
    }
}
