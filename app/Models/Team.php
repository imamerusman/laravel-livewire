<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'designation',
        'image',
    ];
    //image
    public function getImageAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
