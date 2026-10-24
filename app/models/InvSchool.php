<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvSchool extends Model
{
    protected $table = 'inv_schools';

    protected $fillable = [
        'school_name', 'address', 'phone', 'email', 'principal_name', 'principal_phone',
        'inventory_officer_name', 'inventory_officer_phone', 'username', 'password', 'logo_path',
    ];

    protected $hidden = ['password'];

    public function sections(): HasMany
    {
        return $this->hasMany(InvSection::class, 'school_id');
    }

    public function officers(): HasMany
    {
        return $this->hasMany(InvOfficer::class, 'school_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvItem::class, 'school_id');
    }

    public function checkSessions(): HasMany
    {
        return $this->hasMany(InvCheckSession::class, 'school_id');
    }
}
