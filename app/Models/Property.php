<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class property extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'state',
        'type',
        'bedrooms',
        'address',
        'price_per_annum',
        'image',
        'upload_successfull',
        'disk'
    ];
    public function user(){
        return$this->belongsTo(User::class);
    }
    
    public function galleries(){
        return $this->hasMany(Gallery::class);
    }

    public function getImagesAttribute()
    {
        return [
            "thumbnail" => $this->getImagePath("thumbnail"),
            "original" => $this->getImagePath("original"),
            "large" => $this->getImagePath("large"),
        ];
    }

    
    public function galleryImagesAttribute()
    {
        $galleryImageLinks = [];
    
        foreach($this->galleries as $gallery){
            array_push($galleryImageLinks, $this->getGalleryImagePath($gallery->disk, $gallery->image));
        }
        return $galleryImageLinks;
    }

    public function getGalleryImagePath($disk){
        return Storage::disk($disk)->url("uploads/properties/gallery/" . $image);
    }

    public function getImagePath($size)
    {
        return Storage::disk($this->disk)->url("uploads/properties/($size)/" . $this->image);
    }
}
