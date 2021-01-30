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
    return view('welcome');
})->name('main');

Auth::routes([
    'register' => false,
    'reset' => false
]);

Route::get('/home', function(){
    return redirect()->route('admin.home');
});
Route::post('/inbox/apdet', 'InboxController@apdet')->name('inbox.apdet');
Route::post('/inbox/unduh', 'InboxController@unduh')->name('inbox.unduh');

Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function(){
    Route::get('/', 'DashboardController@index')->name('home');

    Route::resource('inboxes','InboxController', ['except' => ['show', 'store', 'add']]);
    Route::any('/inboxes/search','InboxController@search')->name('inboxes.search');
    Route::any('/inboxes/search','InboxController@search')->name('inboxes.search');
    Route::post('/inboxes/deletemass','InboxController@deletemass')->name('inboxes.deletemass');
    Route::get('/inboxes/apicek','InboxController@apicek')->name('inboxes.apicek');
    
    Route::resource('users','UserController', ['except' => ['show']]);
    Route::any('/users/search','UserController@search')->name('users.search');
    Route::post('/users/deletemass','UserController@deletemass')->name('users.deletemass');
    Route::get('/users/profile', 'UserController@profile')->name('users.profile');

    Route::resource('terminals','TerminalController', ['except' => ['show']]);
    Route::any('/terminals/search','TerminalController@search')->name('terminals.search');
    Route::post('/terminals/deletemass','TerminalController@deletemass')->name('terminals.deletemass');
});