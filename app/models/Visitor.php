<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $table = 'visitors';

    protected $fillable = ['name', 'phone', 'purpose', 'person_to_meet', 'visit_date', 'time_in', 'time_out'];

    protected $casts = ['visit_date' => 'date'];
}
