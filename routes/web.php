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

Route::get('/', 'HomeController@index')->name("home");

//discrepancies
Route::group(['prefix'=>'/discrepancies'], function () {
    Route::get('/', 'DiscrepancyController@get_discrepancy_records')->name("discrepancies");
    Route::get('/archive', 'DiscrepancyController@get_discrepancy_archive')->name("archive_discrepancies");
//    Route::post('/add', 'AreaController@add_area');
//    Route::post('/update', 'AreaController@update_area');
    Route::delete('/delete', 'DiscrepancyController@delete_discrepancy_record')->name("discrepancies_delete");
});

Auth::routes(['register'=>false, 'reset'=>false]);
Route::get('/logout', 'Auth\LoginController@logout')->name("logout");
