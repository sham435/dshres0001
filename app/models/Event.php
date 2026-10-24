<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title','description','location','event_date','start_time','image'];
    protected $casts = ['event_date' => 'date'];
}
