<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'startDate', 'endDate', 'description', 'image', 'category_id'];

    // Many-to-many relationship with users through the pivot table users_events
    public function users()
    {
        return $this->belongsToMany(User::class, 'users_events', 'event_id', 'user_id');
    }

    // Many-to-one relationship with category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
