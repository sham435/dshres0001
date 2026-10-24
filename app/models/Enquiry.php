<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $table = 'enquiries';

    protected $fillable = ['name', 'phone', 'email', 'enquiry_for', 'message', 'follow_up_date', 'status'];

    protected $casts = ['follow_up_date' => 'date'];

    public const STATUSES = ['new', 'contacted', 'admitted', 'closed'];
}
