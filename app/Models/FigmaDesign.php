<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FigmaDesign extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    const MEDIA_COLLECTION = "figma_media";
    protected $fillable = [
        'figma_cat_id',
        'name',
        'description',
        'image',
        'src',
    ];
    //image
    public function getImageAttribute($value)
    {
        return asset('storage/' . $value);
    }
    //figma_cat_id
    public function figma_cat()
    {
        return $this->belongsTo(FigmaCategory::class);
    }

    public function splashScreens(): HasMany
    {
        return $this->hasMany(SplashScreen::class, 'figma_design_id');
    }

}
