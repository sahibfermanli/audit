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
    Route::get('/add', 'DiscrepancyController@get_add_discrepancy')->name("get_add_discrepancy");
    Route::post('/add', 'DiscrepancyController@post_add_discrepancy')->name("post_add_discrepancy");
    Route::get('/update/{id}', 'DiscrepancyController@get_update_discrepancy')->name("get_update_discrepancy");
    Route::post('/update/{id}', 'DiscrepancyController@post_update_discrepancy')->name("post_update_discrepancy");
    Route::delete('/delete', 'DiscrepancyController@delete_discrepancy_record')->name("discrepancies_delete");
    Route::delete('/delete/document', 'DiscrepancyController@delete_discrepancy_document')->name("delete_discrepancy_document");

    Route::group(['prefix'=>'/{discrepancy_id}/correctives'], function () {
        Route::get('/', 'CorrectiveActionController@get_corrective_actions')->name("corrective_action_list");
        Route::get('/add', 'CorrectiveActionController@get_add_corrective')->name("get_add_corrective");
        Route::post('/add', 'CorrectiveActionController@post_add_corrective')->name("post_add_corrective");
        Route::delete('/delete', 'CorrectiveActionController@delete_corrective')->name("delete_corrective");
        Route::get('/update/{id}', 'CorrectiveActionController@get_update_corrective')->name("get_update_corrective");
        Route::post('/update/{id}', 'CorrectiveActionController@post_update_corrective')->name("post_update_corrective");
        Route::delete('/delete/document', 'CorrectiveActionController@delete_corrective_document')->name("delete_corrective_document");
    });
});

Auth::routes(['register'=>false, 'reset'=>false]);
Route::get('/logout', 'Auth\LoginController@logout')->name("logout");
