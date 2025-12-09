<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model {
    protected $fillable = ['user_id','bookable_id','bookable_type','date','guests','total_price','status'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function bookable() {
        return $this->morphTo();
    }
}

