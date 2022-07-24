<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\AuthController;

Route::group(['middleware' =>'auth:sanctum'],function(){
    Route::post('password/update',[AuthController::class,'updatePassword']);

    
    Route::get('properties/{propertyId}',[PropertyController::class, 'getProperty']);
    Route::put('properties/{propertyId}',[PropertyController::class, 'updateProperty']);
    Route::post('properties',[PropertyController::class, 'createProperty']);
    Route::delete('properties/{propertyId}',[PropertyController::class, 'deleteProperties']);
    Route::post('logout',[AuthController::class,'logout']);
    Route::post('properties//{propertyId}gallery',[GalleryController::class, 'uploadImageToGallery']);
});

Route::get('properties',[PropertyController::class, 'getAllProperties']);
Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::get('propertie/search',[PropertyController::class,'search']);

