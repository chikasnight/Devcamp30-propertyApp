<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Http\Resources\PropertyResource;

use App\Jobs\UploadImage;
use Illuminate\Http\Request;
use Str;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    //adding property to db table
    public function createProperty(Request $request){
        //validate request body
          $request->validate([
            'name'=>['required','min:5','unique:properties,name'],
            'state'=>['required'],
            'type'=>['required', 'in:buy,rent,shortlet'],
            'bedrooms'=>['required'],
            'address'=>['required', 'integer'],
            'price_per_annum'=>['required', 'string'],
            'image' => ['mimes:png,jpeg,gif,bmp', 'max:2048'],

        ]);
        //get the image
        $image = $request->file('image');
        //$image_path = $image->getPathName();

        // get original file name and replace any spaces with _
        // example: ofiice card.png = timestamp()_office_card.pnp
        $filename = time()."_".preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));

        // move image to temp location (tmp disk)
        $tmp = $image->storeAs('uploads/original', $filename, 'tmp');


    
        //ad property to db
       $newProperty= Property::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'state' => $request->state,
            'type' => $request->type, 
            'bedrooms' => $request->bedrooms,
            'image'=> $request->image,
            'disk'=> config('site.upload_disk'),
            'price_per_annum' => $request-> price_per_annum,
            'address'=> $request->address
       ]);
       //dispacth job to handle image manipulation
       $this->dispatch(new UploadImage($newProperty));


        //return success
        return response()->json([
            'success'=> true,
            'message'=>'success',
            'data' => new PropertyResource($newProperty),
        ]);
    }
    public function getAllProperties(){
        $postAll = Property::all();

        return response() ->json([
            'success'=> true,
            'data'  => PropertyResource::collection($postAll) 
            
        ]);
    }

    public function getProperty(Request $request, $propertyId){
        $property = Property::find($propertyId);
        if(!$property) {
            return response() ->json([
                'success' => false,
                'message' => 'property not found'
            ]);
        }

        return response() ->json([
            'success'=> true,
            'message'  => 'property found',
            'data'   => [
                'property'=> new PropertyResource( $property),
                
            ]
        ]);
    }
    public function updateProperty(Request $request, $propertyId){
        $request->validate([
            'name'=>['required','min:5','unique:properties,name', $propertyId],
            'state'=>['required'],
            'type'=>['required'],
            'bedrooms'=>['required'],

        ]);
        
        $property = Property::find($propertyId);
        if(!$prperty) {
            return response() ->json([
                'success' => false,
                'message' => 'property not found'
            ]);

        $this->authorize('update',$property);

        }

        $property->name = $request->name;
        $property->slug = Str::slug($request->name);
        $property->state = $request->state;
        $property->type = $request->type;
        $property->bedrooms = $request->bedrooms;
        $property->save();
    }
    public function deleteProperty( $propertyId){

        $property = Property::find($propertyId);
        if(!$property) {
            return response() ->json([
                'success' => false,
                'message' => 'property not found'
            ]);
        }
        //deleting images ssociated with the thumnbnail
        foreach(['thumbnail', 'large', 'original'] as $size){
            if(Storage::disk($property->disk)->delete("uploads/properties/($size)/", $property->image)){
                Storage::disk($property->disk)->delete("uploads/properties/($size)/", $property->image);
             
            }

        }
        foreach($property->galleries as $gallery){
            if(Storage::disk($gallery->disk)->delete("uploads/properties/gallery/", $gallery->image)){
                (Storage::disk($gallery->disk)->delete("uploads/properties/gallery/", $gallery->image));
             
            }

        }


        //delete property
        $property-> delete();

        return response() ->json([
            'success' => true,
            'message' => 'property deleted'
            ]); 
    }

    public function search(Request $request){
        $property =new Property();
        $query =$property-> newQuery();

        if($request->has('state')){
            $query= $query->where('state', $request->state);
        
        }

        if($request->has('type')){
            $query= $query->where('type', $request->type);
        }
        
        if($request->has('bedrooms')){
            $query= $query->where('bedrooms', $request->bedrooms);
        }

         
        if($request->has('minprice')){
            $query->where('price_per_annum','>=', $request->minprice);

        }

        if($request->has('maxprice')){
            $query->where('price_per_annum','<=', $request->maxprice);

        }

        
        return response()->json([
            'success'=> true,
            'message'=>'search results found',
            'data'=> $query->get()

            
        ]);
        
    }


    
}
