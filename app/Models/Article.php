<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    # Cuál es el significado de esta línea
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function imageUrl()
    {
        return $this->image
            // Storage::disk('public')->url($this->image)
            ? asset('storage/' . $this->image)
            : 'https://via.placeholder.com/640x480.png/6366f11?text=no-image';
    }
}
