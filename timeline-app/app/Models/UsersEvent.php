<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersEvent extends Model
{
    use HasFactory;

    protected $table = 'users_events'; // Specify the table name

    // Optionally, you can define the fillable properties if needed
    protected $fillable = ['user_id', 'event_id'];
}
