<?php

namespace App\Jobs;

use App\Models\Gallery;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\File;
use Exception;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Image;

class uploadImageToGallery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $gallery;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Gallery $gallery)
    {
        $this->gallery = $gallery;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $disk = $this->gallery->disk;
        //Log::info("Disk: " . $disk);
        $imageName = $this->gallery->image;
        $original_file = storage_path() . '/uploads/gallery/' . $imageName;

        try {

            // store images to permanent disk

            // Original
            if (Storage::disk($disk)->put('/uploads/properties/gallery/' . $imageName, fopen($original_file, 'r+'))) {
                File::delete($original_file);
            }

            // update database record with success flag
            $this->gallery->update([
                'upload_successful' => true
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
    
}
