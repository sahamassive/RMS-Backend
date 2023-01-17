<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Branch;

class Restuarant extends Model
{
    use HasFactory;

    // public function branch(){
    //     return $this->hasMany(Branch::class, 'restaurant_id');
    // }
}
