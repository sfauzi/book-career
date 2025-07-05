<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;


class Task extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'company_name',
        'position',
        'applied_date',
        'status',
        'platform',  // Menambahkan field platform
        'notes',   // Menambahkan field catatan
        'is_closed',   // Menambahkan field catatan
    ];


    protected $casts = [
        'applied_date' => 'date',
    ];

    // Event listeners untuk tracking changes
    // Event listeners untuk tracking changes
    protected static function booted()
    {
        // Auto-assign user_id saat create
        static::creating(function ($task) {
            if (!$task->user_id && auth()->check()) {
                $task->user_id = auth()->id();
            }
        });

        static::updated(function ($task) {
            $task->recordHistory();
        });

        // Global scope untuk membatasi data berdasarkan user
        static::addGlobalScope('userScope', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('user_id', auth()->id());
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(History::class, 'task_id')->latest();
    }

    /**
     * Record perubahan ke history table
     */
    public function recordHistory()
    {
        $changes = $this->getChanges();
        $original = $this->getOriginal();

        if (empty($changes)) {
            return;
        }

        $changedFields = [];

        foreach ($changes as $field => $newValue) {
            if ($field === 'updated_at') continue;

            $oldValue = $original[$field] ?? null;

            // Skip if the field is 'is_closed' and value did not actually change
            if ($field === 'is_closed' && (bool)$oldValue === (bool)$newValue) {
                continue;
            }


            $changedFields[$field] = [
                'old_value' => $oldValue,
                'new_value' => $newValue,
                'field_label' => $this->getFieldLabel($field)
            ];
        }

        if (!empty($changedFields)) {
            History::create([
                'task_id' => $this->id,
                'user_id' => $this->user_id,
                'changed_fields' => $changedFields,
                'action' => 'updated',
                'changed_by' => auth()->id(),
            ]);
        }
    }

    /**
     * Get human readable field labels
     */
    private function getFieldLabel($field)
    {
        $labels = [
            'company_name' => 'Nama Perusahaan',
            'position' => 'Posisi',
            'applied_date' => 'Tanggal Melamar',
            'status' => 'Status',
            'platform' => 'Platform',
            'notes' => 'Catatan',
        ];

        return $labels[$field] ?? ucfirst(str_replace('_', ' ', $field));
    }

    /**
     * Get status options
     */
    public static function getStatusOptions()
    {
        return [
            'Applied' => 'Applied',
            'Interview' => 'Interview',
            'Test' => 'Test',
            'Diterima' => 'Diterima',
            'Ditolak' => 'Ditolak',
        ];
    }

    /**
     * Get status label with color
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'Applied' => 'primary',
            'Interview' => 'warning',
            'Test' => 'info',
            'Diterima' => 'success',
            'Ditolak' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }
}
