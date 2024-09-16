<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SplashScreen extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    const MEDIA_COLLECTION = "splash_screen";
    protected $fillable = [
        'figma_design_id',
        'title',
        'image',
        'src'
    ];

    public function figmaDesign(): BelongsTo
    {
        return $this->belongsTo(FigmaDesign::class);
    }
}
