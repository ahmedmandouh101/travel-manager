<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model {
    protected $fillable = ['name','description','type','address','lat','lng','image'];

    public function tours() {
        return $this->hasMany(Tour::class);
    }

    public function reviews() {
        return $this->morphMany(Review::class, 'reviewable');
    }
}

