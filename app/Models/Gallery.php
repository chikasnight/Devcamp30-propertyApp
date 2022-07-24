<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    protected $fillable = [
        'property_id',
        'image',
        'upload_successful',
        'disk',
    ];
    
    public function property(){
        return$this->belongsTo(Property::class);
    }
    
    
   /* public function galleryImagesAttribute()
    {
        return [
            "thumbnail" => $this->getImagePath("thumbnail"),
            "original" => $this->getImagePath("original"),
            "large" => $this->getImagePath("large"),
        ];
    }

    public function getImagePath($size)
    {
        return Storage::disk($this->disk)->url("uploads/properties/{$size}/" . $this->image);
    }*/
}
