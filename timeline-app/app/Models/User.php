<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Use the Authenticatable class
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash; // Import the Hash facade

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    // Specify which attributes are mass assignable
    protected $fillable = [
        'email',
        'password',
        'name',
        'surname', 
    ];

    // This method returns the unique identifier for the JWT
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Returns the user ID
    }

    // This method allows you to add custom claims to the JWT
    public function getJWTCustomClaims()
    {
        return []; // You can return any additional claims you want to add to the JWT
    }

    // Automatically hash the password when setting it
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    // Relationship with events through the pivot table users_events
    public function events()
    {
        return $this->belongsToMany(Event::class, 'users_events', 'user_id', 'event_id');
    }
}