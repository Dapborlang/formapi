<?php
//API
use Rdmarwein\FormApi\Http\Controllers\FormBuilderAPIController;
use Rdmarwein\FormApi\Http\Controllers\FormBuilderController;
Route::group(['middleware' => 'web','namespace'=>'Rdmarwein\FormApi\Http\Controllers'], function()
{
//Create the Form
Route::get('form-api/create/{id}',[FormBuilderAPIController::class, 'create']);
Route::post('form-api/{id}',[FormBuilderAPIController::class, 'store']);
Route::delete('form-api/{fid}/{id}',[FormBuilderAPIController::class, 'destroy']);
Route::get('form-api/dependant/{id}',[FormBuilderAPIController::class, 'getDependant']);
//PUT
Route::put('form-api/{fid}/{id}',[FormBuilderAPIController::class, 'update']);
//get the Edit
Route::get('form-api/edit/{fid}/{id}',[FormBuilderAPIController::class, 'edit']);

//Retrieve the Form
Route::get('form-api/{id}',[FormBuilderAPIController::class, 'index']);

//Consume the api
//Create

Route::get('form-builder/create/{id}',[FormBuilderController::class, 'create']);
//Index
Route::get('form-builder/{id}',[FormBuilderController::class, 'index']);
//Edit
Route::get('form-builder/{fid}/{id}',[FormBuilderController::class, 'edit']);
});