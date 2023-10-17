<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user-register', 'App\Http\Controllers\FrontEndController@user_register');
Route::get('/user-login', 'App\Http\Controllers\FrontEndController@user_login');

Route::post('/visa/store_applications', 'App\Http\Controllers\VisaController@store');
Route::post('/visa/store_offer', 'App\Http\Controllers\OfferController@store');
Route::get('/visa/{nationality}/{destination}', 'App\Http\Controllers\FrontEndController@getOffer_view');



Route::get('/visa/offers/rules/{id}', 'App\Http\Controllers\FrontEndController@getOffer_documentrules');


Route::get('/getfields/{country}', 'App\Http\Controllers\FormsController@getFields');
Route::get('/getfields_status/{selectedIds}', 'App\Http\Controllers\FormsController@getStatus');
Route::get('/getfields_status_enable/{selectedIds}', 'App\Http\Controllers\FormsController@getStatus_enable');



Route::get('/getcurrency/{country}', 'App\Http\Controllers\OfferController@getcurrency');
Route::get('/getnation/{offer_id}', 'App\Http\Controllers\OfferController@getnation');
Route::post('/updatenation/{offer_id}/{nation}', 'App\Http\Controllers\OfferController@update_country');


Route::get('/document/{nationality}/{destination}', 'App\Http\Controllers\FrontEndController@getOffer_json');
Route::get('/getdocument/{document_id}', 'App\Http\Controllers\FrontEndController@createJson');
Route::get('/getdocument/nation/{destination}', 'App\Http\Controllers\FrontEndController@createnationJson');
Route::get('/getTax', 'App\Http\Controllers\FrontEndController@getTaxJson');

Route::post('/offer/update', 'App\Http\Controllers\DocumentRuleController@update_offers');

Route::get('/convert/{country_code}', 'App\Http\Controllers\CurrencyController@convert_currency');
Route::post('/exchange_rate/{id}/{rate}', 'App\Http\Controllers\CurrencyController@update_rate');
Route::post('/update_tax/{id}/{rate}/{tax_name}', 'App\Http\Controllers\TaxController@update_tax');


Route::post('/ocr_test', 'App\Http\Controllers\OCRController@yourControllerFunction');


Route::get('/getbranches/{country}', 'App\Http\Controllers\FrontEndController@getbranches');
Route::get('/getagency/{country}/{branch}', 'App\Http\Controllers\FrontEndController@getagency');

Route::get('/getexport', 'App\Http\Controllers\OfferController@exportData');



