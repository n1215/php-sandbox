<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    $user = new \App\User(['type' => \App\UserType::ADMIN()]);
    dump($user->type->equals(\App\UserType::ADMIN()));

    $user->type = \App\UserType::GENERAL();
    dump($user->type->equals(\App\UserType::GENERAL()));
});
