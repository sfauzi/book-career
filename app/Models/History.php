<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
