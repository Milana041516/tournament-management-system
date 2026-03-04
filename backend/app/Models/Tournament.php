<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'start_date',
        'end_date',
        'max_teams',
        'organizer_id',
        'status',
    ];

    public function organizer() {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function teams() {
        return $this->hasMany(Team::class);
    }

    public function registrations() {
        return $this->hasMany(Registration::class);
    }
}
