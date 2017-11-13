<?php

// Auth::loginUsingId(1);

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

Route::get('threads', 'ThreadsController@index')
    ->name('all.threads.index');

Route::post('/threads/{thread}/replies', 'RepliesController@store')
    ->name('replies.store');
Route::delete('/threads/{reply}', 'RepliesController@destroy')
    ->name('replies.destroy');
Route::patch('/threads/{reply}', 'RepliesController@update')
    ->name('replies.update');

Route::resource('channels', 'ChannelController');
Route::resource('channels/{channel}/threads', 'ThreadsController');

Route::post('/favorites/{reply}', 'FavoritesController@store')
    ->name('favorites.store');
Route::delete('/favorites/{reply}', 'FavoritesController@destroy')
    ->name('favorites.destroy');

Route::get('/home', 'HomeController@index');

Route::get('/profile/{user}', 'ProfilesController@show')
    ->name('profile.show');

Route::post('/subscribe/{thread}', 'SubscriptionsController@store')
    ->name('subscription.store');
Route::delete('/subscribe/{thread}', 'SubscriptionsController@destroy')
    ->name('subscription.destroy');

Route::get('/notifications', 'NotificationsController@index')
    ->name('notification.index');

Route::get('/notifications/read', 'NotificationsController@markAsRead')
    ->name('notification.read');

Auth::routes();
