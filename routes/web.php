<?php
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

Route::get('/', 'HomeController@index')->name('home.index');
Route::group(['namespace'=>'Frontend'], function() {
    Route::resource('/sendfeedback', 'FeedBackController', ['only' => ['create', 'store']]);
    Route::group(['middleware'=> 'auth'], function() {
        Route::put('/profile/{profile}/reservation/{reservation}', 'ReservationController@update')->name('user.cancelBooking');
        Route::group(['middleware'=> 'checkUser'], function() {
            Route::resource('/profile', 'UserController');
            Route::get('/profile/{profile}/reservation/{reservation}/show', 'ReservationController@show')->name('user.showBooking');
        });
    });
    Route::get('hotels/{slug}', 'HotelController@show')->name('hotels.show');
    Route::resource('/hotels', 'HotelController', ['only' => ['index']]);
    Route::resource('/news', 'NewsController', ['as' => 'frontend']);
    Route::get('/places/hintPlaces', 'PlaceController@hintPlaces')->name('places.hintPlaces');
});
Route::get('/registerSuccess', function() {
    return view('frontend.notice');
})->name('notice')->middleware('auth');
Route::group(['namespace'=>'Admin', 'prefix'=>'admin', 'middleware'=>'adminLogin'], function() {
    Route::get('/', 'AdminController@index')->name('admin.index');

    Route::resource('/user', 'UserController');
    Route::resource('/place', 'PlaceController');
    Route::resource('/comment', 'RatingCommentController');
    Route::resource('/news', 'NewsController');
    Route::resource('/hotel', 'HotelController');
    Route::group(['prefix'=>'hotel/{hotel}'], function($hotel) {
        Route::resource('/room', 'RoomController');
    });
    Route::resource('/image', 'ImageController', ['only' => ['destroy']]);
    Route::resource('/category', 'CategoryController');
    Route::resource('/feedback', 'FeedbackController');
    Route::resource('/static-page', 'StaticPageController');
    Route::resource('/service', 'ServiceController', ['except' => ['show']]);
    Route::resource('reservation', 'ReservationController', ['except' => ['create','store']]);

    Route::put('/user/{id}/status', 'UserController@updateStatus')->name('user.updateStatus');
    Route::put('/user/{id}/role', 'UserController@updateRole')->name('user.updateRole');
});

Auth::routes();
