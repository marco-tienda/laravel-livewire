<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    # Cuál es el significado de esta línea
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
