<?php
use App\Http\Controllers\PropertyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('properties',[PropertyController::class, 'getAllProperties']);
Route::get('properties/{propertyId}',[PropertyController::class, 'getProperty']);
Route::put('properties/{propertyId}',[PropertyController::class, 'updateProperty']);
Route::post('properties',[PropertyController::class, 'createProperty']);
Route::delete('properties/{propertyId}',[PropertyController::class, 'deleteProperties']);

