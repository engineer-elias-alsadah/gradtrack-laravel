<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'milestone_id',
        'user_id',
        'version',
        'notes',
        'github_url',
        'attachment_path',
        'is_late',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'is_late' => 'boolean',
            'submitted_at' => 'datetime',
            'version' => 'integer',
        ];
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function latestReview(): HasOne
    {
        return $this->hasOne(Review::class)->latestOfMany();
    }
}
