<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model {
    protected $fillable = ['title','description','place_id','price','duration_hours'];

    public function place() {
        return $this->belongsTo(Place::class);
    }

    public function reviews() {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function bookings() {
        return $this->morphMany(Booking::class, 'bookable');
    }
}

