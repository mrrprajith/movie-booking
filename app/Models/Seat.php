<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    public function bookingSeats() {
        return $this->hasMany(\App\Models\BookingSeat::class);
    }
}
