<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvItem extends Model
{
    protected $table = 'inv_items';

    protected $fillable = [
        'school_id', 'item_name', 'brand_name', 'inventory_number', 'serial_number',
        'section_id', 'book_number', 'incharge_officer_id', 'received_date', 'received_from',
        'is_transferred', 'transferred_date', 'borrowed_officer', 'condition_status', 'image_path',
    ];

    protected $casts = [
        'is_transferred' => 'boolean',
        'received_date' => 'date',
        'transferred_date' => 'date',
    ];

    public const CONDITIONS = ['working', 'good', 'not_working', 'not_usable'];

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeSearch($query, ?string $search)
    {
        if (empty($search)) {
            return $query;
        }
        return $query->where(function ($q) use ($search) {
            $q->where('item_name', 'like', "%{$search}%")
                ->orWhere('inventory_number', 'like', "%{$search}%")
                ->orWhere('serial_number', 'like', "%{$search}%");
        });
    }

    public function scopeOfSection($query, $sectionId)
    {
        if (empty($sectionId)) {
            return $query;
        }
        return $query->where('section_id', $sectionId);
    }

    public function scopeOfCondition($query, ?string $condition)
    {
        if (empty($condition)) {
            return $query;
        }
        return $query->where('condition_status', $condition);
    }

    public function scopeWithStatus($query, ?string $status)
    {
        if ($status === 'transferred') {
            return $query->where('is_transferred', true);
        }
        if ($status === 'borrowed') {
            return $query->whereNotNull('borrowed_officer')->where('borrowed_officer', '!=', '');
        }
        if ($status === 'available') {
            return $query->where('is_transferred', false)
                ->where(function ($q) { $q->whereNull('borrowed_officer')->orWhere('borrowed_officer', ''); });
        }
        return $query;
    }

    public function scopeFiltered($query, array $filters)
    {
        return $query->search($filters['search'] ?? null)
            ->ofSection($filters['section'] ?? null)
            ->ofCondition($filters['condition'] ?? null)
            ->withStatus($filters['status'] ?? null);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(InvSchool::class, 'school_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(InvSection::class, 'section_id');
    }

    public function incharge(): BelongsTo
    {
        return $this->belongsTo(InvOfficer::class, 'incharge_officer_id');
    }

    public function statusLabel(): string
    {
        if ($this->is_transferred) {
            return 'transferred';
        }
        if (!empty($this->borrowed_officer)) {
            return 'borrowed';
        }
        return 'available';
    }
}
