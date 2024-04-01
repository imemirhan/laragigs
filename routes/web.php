<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Listing;
use League\CommonMark\Parser\Block\SkipLinesStartingWithLettersParser;
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

//Listing Section ----------------------------------------------------------------------------------------

// Common resource Routes:
// index - Show all listings
// show - Show a single listing
// create - Show form to create a new listing
// store - Store new listing
// edit - Update an existing listing
//destroy - Delete an existing listing

//All listings
Route::get(
    '/',
    [ListingController::class, 'index']
);


//Show create form
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth');


//Store Listing Data
Route::post(
    '/listings',
    [ListingController::class, 'store']
)->middleware('auth');

//Show Edit Form
Route::get(
    'listings/{listing}/edit',
    [ListingController::class, 'edit']
)->middleware('auth');

//Edit Submit to Update
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');

//Delete an Existing Listing
Route::delete('listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth');

//Manage Listing
Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');

//Single Listing
Route::get(
    '/listings/{listing}',
    [ListingController::class, 'show']
);

//End Listing Section -------------------------------------------------------------------------------------

//User Actions Region -------------------------------------------------------------------------------------
//Show register create form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

//Create New User
Route::post('/users', [UserController::class, 'store']);

//Log user out
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

//Show Login Form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

//Login User
Route::post('/users/authenticate', [UserController::class, 'authenticate']);


//End user Actions Region ---------------------------------------------------------------------------------