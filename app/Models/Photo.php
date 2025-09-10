<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photo extends Model

{
    // use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'location',
        'photo_category',
        'camera_brand',
        'gear_used',
        'photo_taken',
        'photo_path',
    ];
}
