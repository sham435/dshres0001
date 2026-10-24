<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    protected $fillable = ['title','excerpt','body','image','published_at','is_published'];
    protected $casts = ['published_at' => 'date', 'is_published' => 'boolean'];
    public function scopePublished($q) { return $q->where('is_published', true)->orderByDesc('published_at'); }
}
