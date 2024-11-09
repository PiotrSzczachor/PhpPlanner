<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    // One-to-many relationship with events
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
