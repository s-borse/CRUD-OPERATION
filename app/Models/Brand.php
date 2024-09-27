<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
     protected $table = 'brands';

    // Define fillable attributes if needed
    protected $fillable = ['name', 'image'];
}



