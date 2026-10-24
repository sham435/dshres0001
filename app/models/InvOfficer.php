<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvOfficer extends Model
{
    protected $table = 'inv_officers';

    protected $fillable = [
        'school_id', 'section_id', 'officer_name', 'officer_id_number', 'phone', 'is_incharge',
    ];

    protected $casts = ['is_incharge' => 'boolean'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(InvSchool::class, 'school_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(InvSection::class, 'section_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvItem::class, 'incharge_officer_id');
    }
}
