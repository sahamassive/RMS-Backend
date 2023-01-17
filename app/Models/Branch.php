<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Restuarant;

class Branch extends Model
{
    use HasFactory;

    // public function restaurant(){
    //     return $this->belongsTo(Restuarant::class, 'restaurant_id');
    // }
}
