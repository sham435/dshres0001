<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvSection extends Model
{
    protected $table = 'inv_sections';

    protected $fillable = ['school_id', 'section_name', 'section_type', 'main_section'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(InvSchool::class, 'school_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvItem::class, 'section_id');
    }

    public function officers(): HasMany
    {
        return $this->hasMany(InvOfficer::class, 'section_id');
    }

    public function inchargeOfficer()
    {
        return $this->hasOne(InvOfficer::class, 'section_id')->where('is_incharge', true);
    }

    public function incharge(): ?InvOfficer
    {
        return $this->inchargeOfficer()->first();
    }
}
