<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class History extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'task_id',
        'user_id',
        'changed_fields',
        'action',
        'changed_by',
    ];

    protected $casts = [
        'changed_fields' => 'array',
        'created_at' => 'datetime',
    ];

    protected static function booted()
    {
        // Auto-assign user_id saat create
        static::creating(function ($history) {
            if (!$history->user_id && auth()->check()) {
                $history->user_id = auth()->id();
            }
        });

        // Global scope untuk membatasi data berdasarkan user
        static::addGlobalScope('userScope', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('user_id', auth()->id());
            }
        });
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Get formatted change description
     */
    public function getChangeDescriptionAttribute()
    {
        $descriptions = [];

        foreach ($this->changed_fields as $field => $change) {
            $label = $change['field_label'];
            $oldValue = $change['old_value'] ?? 'kosong';
            $newValue = $change['new_value'] ?? 'kosong';

            $descriptions[] = "{$label}: '{$oldValue}' → '{$newValue}'";
        }

        return implode(', ', $descriptions);
    }

    /**
     * Get action label
     */
    public function getActionLabelAttribute()
    {
        $actions = [
            'created' => 'Dibuat',
            'updated' => 'Diperbarui',
            'deleted' => 'Dihapus',
        ];

        return $actions[$this->action] ?? ucfirst($this->action);
    }
}
