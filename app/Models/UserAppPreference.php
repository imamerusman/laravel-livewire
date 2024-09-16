<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class UserAppPreference extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia;
    const MEDIA_COLLECTION = "app_logo";
    protected $fillable = [
        'user_id',
        'theme',
        'product',
        'drawer',
        'navbar',
        'splash',
        'finalize',
        'status',
        'meta_data',
        'is_completed',
        'primary_color',
        'secondary_color',
    ];



    protected $casts = [
        'meta_data' => 'array',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function figmaDesign(): BelongsTo
    {
        return $this->belongsTo(FigmaDesign::class,'theme');
    }
}
