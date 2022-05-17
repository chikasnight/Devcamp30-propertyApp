<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use str;

class PropertyController extends Controller
{
    //adding property to db table
    public function createProperty(Request $request){
        //validate request body
        $request->validate([
            'name'=>['required','min:5','unique:properties,name'],
            'state'=>['required'],
            'type'=>['required'],
            'bedroom'=>['required'],

        ]);
        //ad property to db
       $newProperty=propery::create([
            'user_id' => 1,
            'name' => $request->name,
            'slup' =>str::slug($request->name),
            'state' => $request->state,
            'type' => $request->type, 
            'bedroom' => $request->bedroom,
       ]);
        //return success
        return response()->json([
            'success'=> true,
            'message'=>'success',
            'data' => $newProperty,
        ]);
    }
    public function getAllProperties(){}
    public function getProperty(){}
    public function updateProperty(){}
    public function deleteProperty(){}
}
