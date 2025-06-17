<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    public function seats() {
        return $this->hasMany(\App\Models\Seat::class);
    }
}
