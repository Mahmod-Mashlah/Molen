<?php

use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*Route::get('/send', function () {
    // return 'its ok';
    // return view('welcome');

    // Mail::to($request->user())->send(new OrderShipped($order));
    Mail::to('mashlahmahmod@gmail.com')->send(new TestMail());
    return response()->json('sending 😎', 200,);
});
*/

