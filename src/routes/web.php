<?php

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

Route::get('/test', function () {
    return view('welcome');
});

Route::post('/api/jwt/login', function () {
    // return view('welcome');
    $json = '{"accessToken":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MSwiaWF0IjoxNjg2MjE2MTYxLCJleHAiOjE2ODYyMTY0NjF9.sYAPpaOOnjGyPpAtAaen9QxWkF1HKPRPdnFKmdCxBSs","userData":{"id":1,"role":"admin","fullName":"John Doe","username":"johndoe","email":"admin@vuexy.com"}}';
    return json_decode($json);
});
