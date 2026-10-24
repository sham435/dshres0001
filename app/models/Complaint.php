<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = 'complaints';

    protected $fillable = ['complainant', 'phone', 'subject', 'description', 'status', 'resolution'];

    public const STATUSES = ['open', 'in_review', 'resolved', 'closed'];
}
