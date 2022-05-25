<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Str;

class PropertyController extends Controller
{
    //adding property to db table
    public function createProperty(Request $request){
        //validate request body
        $request->validate([
            'name'=>['required','min:5','unique:properties,name'],
            'state'=>['required'],
            'type'=>['required'],
            'bedrooms'=>['required'],

        ]);
        //ad property to db
       $newProperty= Property::create([
            'user_id' => 1,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'state' => $request->state,
            'type' => $request->type, 
            'bedrooms' => $request->bedrooms,
       ]);
        //return success
        return response()->json([
            'success'=> true,
            'message'=>'success',
            'data' => $newProperty,
        ]);
    }
    public function getAllProperties(){
        $postAll = Property::all();

        return response() ->json([
            'success'=> true,
            'data'  => $postAll
            
        ]);
    }

    public function getProperty(Request $request, $propertyId){
        $property = Property::find($propertyId);
        if(!$prperty) {
            return response() ->json([
                'success' => false,
                'message' => 'property not found'
            ]);
        }

        return response() ->json([
            'success'=> true,
            'message'  => 'property found',
            'data'   => $property
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
        if(!$prperty) {
            return response() ->json([
                'success' => false,
                'message' => 'property not found'
            ]);
        }
        $property-> delete();

        return response() ->json([
            'success' => true,
            'message' => 'property deleted'
        ]); 
    }
}
