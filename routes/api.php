<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// use App\Http\Controllers\NoteController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// public Routes

Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);

// protected Routes (With Auth)

// Route::prefix()-> group(['middleware'=>['auth:sanctum']],function () {} //to implement prefix

Route::group(['middleware'=>['auth:sanctum']],function () {

/************************************* Admin Routes ******************************************/
/**********************************************************************************************/
Route::resource('/doctors', NoteController::class);

// Route::resource('/tasks', TaskController::class);

/************************************* Doctor Routes ******************************************/
/**********************************************************************************************/

// Route::resource('/tasks', TaskController::class);

Route::resource('/allergies', AllergyController::class);
Route::resource('/analyzings', AnalyzingController::class);
Route::resource('/notes', NoteController::class);
Route::resource('/days', DayController::class);
Route::resource('/exams', ExamController::class);
Route::resource('/families', FamilyController::class);
Route::resource('/personalizings', PersonalizingController::class);
Route::resource('/prescriptions', PrescriptionController::class);
Route::resource('/profiles', ProfileController::class);
Route::resource('/rays', RayController::class);
Route::resource('/reservations', ReservationController::class);
Route::resource('/states', StateController::class);
Route::resource('/sugar-tests', SugarTestController::class);
Route::resource('/surgeries', SurgeryController::class);
Route::resource('/treatments', TreatmentController::class);
Route::resource('/users', DoctorController::class);
Route::resource('/vaccines', VaccineController::class);
Route::resource('/workTimes', WorkTimeController::class);

/************************************* User Routes ******************************************/
/**********************************************************************************************/

// Route::resource('/tasks', TaskController::class);
Route::resource('/ratings', RatingController::class);


Route::post('/logout', [AuthController::class,'logout']);

});
