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

Route::get('/', 'ThreadsController@index')
    ->name('all.threads.index');

Route::get('threads', 'ThreadsController@index')
    ->name('all.threads.index');

Route::post('/threads/{thread}/replies', 'RepliesController@store')
    ->name('replies.store');
Route::delete('/reply/{reply}', 'RepliesController@destroy')
    ->name('replies.destroy');
Route::patch('/reply/{reply}', 'RepliesController@update')
    ->name('replies.update');
Route::post('/best-reply/{reply}', 'BestReplyController@store')
    ->name('best.reply.store');
Route::delete('/best-reply/{reply}', 'BestReplyController@destroy')
    ->name('best.reply.destroy');

Route::resource('channels', 'ChannelController');
Route::resource('channels/{channel}/threads', 'ThreadsController');
Route::post('/threads/{thread}/lock', 'ThreadLockController@lock')
    ->name('threads.lock');
Route::post('/threads/{thread}/unlock', 'ThreadLockController@unlock')
    ->name('threads.unlock');

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

Route::get('/api/users', 'Api\UsersController@index')
    ->name('api.users.index');

Route::post('/api/avatar/{user}', 'Api\AvatarController@store')
    ->name('api.avatar.store');

Route::get('/confirm/{token}', 'Auth\ConfirmationController@confirm')
    ->name('confirm');

Auth::routes();
