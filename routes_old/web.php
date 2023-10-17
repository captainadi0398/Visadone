<?php

use App\Http\Controllers\OCRController;
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

// Protected routes
Route::get('/mailtest', 'App\Http\Controllers\MailController@sendEmail');
Route::get('/mailtest_agent', 'App\Http\Controllers\MailController@sendEmail_agent');
Route::get('/mailtest_agency', 'App\Http\Controllers\MailController@sendEmail');
Route::get('/', function () {return view('index');});
Route::get('/zoom', function () {return view('testing.zoom_test');});


Route::match(['get', 'post'], '/botman','App\Http\Controllers\BotManController@handle');
// Add more protected routes here
Route::get('/auth/login', 'App\Http\Controllers\BackEndController@user_login');
Route::get('/register', 'App\Http\Controllers\BackEndController@user_register');
Route::get('/profile', 'App\Http\Controllers\BackEndController@user_profile');

Route::post('/user_store', 'App\Http\Controllers\BackEndController@user_register_store')->name('user.store');
Route::post('/users/store', 'App\Http\Controllers\UserController@store')->name('users.store');

Route::get('/logout', 'App\Http\Controllers\BackEndController@logout')->name('logout');

// OCR Routes
Route::post('/upload', [OCRController::class, 'uploadFile'])->name('upload.file');

//OCR Routes END


Route::middleware(['auth_test'])->group(function () {

    Route::get('/', 'App\Http\Controllers\HomeController@home');

    // Routes that require authentication
    Route::get('/auth_users', 'App\Http\Controllers\UserController@index');

// ----------------------------------------------------------------------------------------------------------
    // For Users
    Route::get('/users', 'App\Http\Controllers\UserController@index')->name('users.index');
    Route::get('/users/create', 'App\Http\Controllers\UserController@create');
    Route::get('/users/edit/{id}', 'App\Http\Controllers\UserController@edit')->name('edit_user');
    Route::post('/users/update', 'App\Http\Controllers\UserController@update');

// ---------------------------------------------------------------------------------------------------------
    // For Visa
    Route::get('/visa', 'App\Http\Controllers\VisaController@index');
    Route::get('/visa/create', 'App\Http\Controllers\VisaController@create')->name('visa.create');
    Route::post('/visa/store_applications', 'App\Http\Controllers\VisaController@store');

    Route::get('/visa/offers', 'App\Http\Controllers\OfferController@index');
// ----------------------------------------------------------------------------------------------------------
    // For User Role

    Route::get('/user_role', 'App\Http\Controllers\RolesController@index');
    Route::post('/roles/store', 'App\Http\Controllers\RolesController@store');

// ----------------------------------------------------------------------------------------------------------

// ----------------------------------------------------------------------------------------------------------
    // For Currency
    Route::get('/currency', 'App\Http\Controllers\CurrencyController@index');

// ----------------------------------------------------------------------------------------------------------

// ----------------------------------------------------------------------------------------------------------
    // For Taxes
    Route::get('/tax', 'App\Http\Controllers\TaxController@index');
    Route::post('/submit_tax', 'App\Http\Controllers\TaxController@store');

// ----------------------------------------------------------------------------------------------------------

// For User Role Permission Assign
    Route::get('/permission', 'App\Http\Controllers\AssignPermissionController@index')->name('role.list');
    Route::get('/permission_list/{id}', 'App\Http\Controllers\AssignPermissionController@assigned')->name('assign.permission');
    Route::get('/permission_add', 'App\Http\Controllers\AssignPermissionController@add')->name('assign.add');

    Route::post('/role_store', 'App\Http\Controllers\AssignPermissionController@store_role')->name('role.store');


    Route::post('/permission_store', 'App\Http\Controllers\AssignPermissionController@store')->name('perrole.store');
    Route::post('/permission_update', 'App\Http\Controllers\AssignPermissionController@update')->name('perrole.update');


// ------------------------------------------------------------------------------------------------------------


// For AJAX Routes
    Route::get('/offers/get/{destination}/{type}', 'App\Http\Controllers\OfferController@getoffers');
    Route::get('/offers/multiple/get/{destination}/{type}', 'App\Http\Controllers\OfferController@getoffers_multiple');

// ----------------------------------------------------------------------------------------------------------

// For Offers

    // Route::group(['middleware' => 'loading-screen'], function () {

        // Redirecting to the FORM page


        // Routes for the index pages
        // Route::get('/getfields_visa/{destination}', 'App\Http\Controllers\FormsController@getFields_visa')->name('getfields_visa');
        Route::post('/getfields_visa/{type}/{destination}/{id}/{count}', 'App\Http\Controllers\FormsController@getFields_visa')->name('getfields_visa');
        Route::get('/getdocument_visa/{type}/{destination}/{id}/{count}/{data_response}', 'App\Http\Controllers\FormsController@getDocuments_visa')->name('getDocument_visa');
        // For User Index


        //For Visa Create Page


        // Track Applications

        Route::get('/search', 'App\Http\Controllers\TrackController@search_name');
        Route::get('/search_range', 'App\Http\Controllers\TrackController@date_range');



    // });

    Route::get('/track', 'App\Http\Controllers\TrackController@index');
    Route::get('/track/{status}', 'App\Http\Controllers\TrackController@status_applications');
    Route::get('/track/{id}/{status}', 'App\Http\Controllers\TrackController@update_status');



    // branchs Index
    Route::get('/branch', 'App\Http\Controllers\BranchController@index');
    Route::get('/branch/create', 'App\Http\Controllers\BranchController@create');
    Route::get('/branch/edit/{id}', 'App\Http\Controllers\BranchController@edit');
    Route::post('/branch/update/{id}', 'App\Http\Controllers\BranchController@update');
    Route::post('/branch/store', 'App\Http\Controllers\BranchController@store');

    // // Agency Index
    Route::get('/agency', 'App\Http\Controllers\AgencyController@index');
    Route::get('/agency/create', 'App\Http\Controllers\AgencyController@create');
    Route::post('/agency/store', 'App\Http\Controllers\AgencyController@store');
    Route::get('/agency/edit/{id}', 'App\Http\Controllers\AgencyController@edit');
    Route::post('/agency/update', 'App\Http\Controllers\AgencyController@update');
    Route::get('/agency/filter', 'App\Http\Controllers\AgencyController@filter_out');

    // // Agent Index
    Route::get('/agents', 'App\Http\Controllers\AgentController@index');
    Route::get('/agents/create', 'App\Http\Controllers\AgentController@create');
    Route::post('/agents/store', 'App\Http\Controllers\AgentController@store');
    Route::get('/agent/edit/{id}', 'App\Http\Controllers\AgentController@edit');
    Route::post('/agents/update', 'App\Http\Controllers\AgentController@update');

    Route::get('/visa/offer_create', 'App\Http\Controllers\OfferController@create');
    Route::post('/visa/store_offer', 'App\Http\Controllers\OfferController@store');
    Route::post('/offer/store_offer', 'App\Http\Controllers\OfferController@store_offer');
    Route::get('/visa/offerget', 'App\Http\Controllers\OfferController@getOffer_view');
    Route::post('offer/update', 'App\Http\Controllers\OfferController@update');
    Route::get('/visa/offer/edit/{id}', 'App\Http\Controllers\OfferController@edit');

// ----------------------------------------------------------------------------------------------------------

// For Offers Rules
    Route::get('/offer/rule/get', 'App\Http\Controllers\DocumentRuleController@index');

    Route::get('/visa/offers/rules/{id}', 'App\Http\Controllers\FrontEndController@getDocuments');
    Route::get('/offers_rules/create', 'App\Http\Controllers\DocumentRuleController@create');
    Route::get('/offers_rules/edit/{id}', 'App\Http\Controllers\DocumentRuleController@edit');
    Route::post('/offers_rule/store', 'App\Http\Controllers\DocumentRuleController@store');
    Route::post('/offers_rule/update', 'App\Http\Controllers\DocumentRuleController@update');
    Route::post('/document_update', 'App\Http\Controllers\DocumentRuleController@update_offers');

// ----------------------------------------------------------------------------------------------------------
    Route::get('/export', 'App\Http\Controllers\ExportController@export')->name('export');

//Dynamic Form

    Route::get('/getfields_view/{country}', 'App\Http\Controllers\FormsController@getFields_view');
    // Route::get('/getfields_visa/{country}/{response}', 'App\Http\Controllers\FormsController@getFields_visa');
    // Route::get('/getfields_visa/{destination}', 'App\Http\Controllers\FormsController@getFields_visa')->name('getfields_visa');

    Route::get('/form_fields', 'App\Http\Controllers\FormsController@index');
    Route::post('/form/store', 'App\Http\Controllers\FormsController@store');

    Route::get('/generate-pdf', 'App\Http\Controllers\PdfController@generatePdf')->name('generate.pdf');
    Route::get('/generate-mail', 'App\Http\Controllers\PdfController@sendPdf');

});

Route::get('/ocr_response', 'App\Http\Controllers\OCRController@yourControllerFunction_response');

Route::get('/login', function () {
    return view('layout.login');
});

Route::get('/dashboard_users', 'App\Http\Controllers\UserController@index');

Route::get('/users_create', function () {
    return view('users.create');
});

Route::get('/test', function () {
    return view('testing.testing_dashboard');
});
Route::get('/sidebar', function () {
    return view('layout.app');
});
Route::get('/footer', function () {
    return view('layout.footer');
});
Route::get('/parts', function () {
    return view('testing.test_dashboard');
});

Route::get('/demo', function () {
    return view('testing.demo');
});

// Route::get('/auth/login', 'App\Http\Controllers\BackEndController@user_login');
// // ----------------------------------------------------------------------------------------------------------
// // For Users
// Route::get('/users', 'App\Http\Controllers\UserController@index');
// Route::get('/users/create', 'App\Http\Controllers\UserController@create');

// // ---------------------------------------------------------------------------------------------------------
// // For Visa
// Route::get('/visa', 'App\Http\Controllers\VisaController@index');
// Route::get('/visa/create', 'App\Http\Controllers\VisaController@create');
// Route::post('/visa/store_applications', 'App\Http\Controllers\VisaController@cstore');

// // ----------------------------------------------------------------------------------------------------------
// // For User Types

// // ----------------------------------------------------------------------------------------------------------

// // For AJAX Routes
// Route::get('/offers/get/{destination}', 'App\Http\Controllers\OfferController@getoffers');

// // ----------------------------------------------------------------------------------------------------------

// // For Offers
// Route::get('/visa/offer_create', 'App\Http\Controllers\OfferController@create');
// Route::post('/visa/store_offer', 'App\Http\Controllers\OfferController@store');

// Route::post('/offer/store_offer', 'App\Http\Controllers\OfferController@store_offer');

// Route::get('/visa/offerget', 'App\Http\Controllers\OfferController@getOffer_view');
// Route::get('/visa/offers', 'App\Http\Controllers\OfferController@index');

// // ----------------------------------------------------------------------------------------------------------

// // For Offers Rules
// Route::get('/offer/rule/get', 'App\Http\Controllers\DocumentRuleController@index');

// Route::get('/visa/offers/rules/{id}', 'App\Http\Controllers\FrontEndController@getDocuments');
// Route::get('/offers_rules/create', 'App\Http\Controllers\DocumentRuleController@create');
// Route::post('/offers_rule/store', 'App\Http\Controllers\DocumentRuleController@store');

// // ----------------------------------------------------------------------------------------------------------

// //Dynamic Form

// Route::get('/getfields_view/{country}', 'App\Http\Controllers\FormsController@getFields_view');
// Route::get('/form_fields', 'App\Http\Controllers\FormsController@index');
// Route::post('/form/store','App\Http\Controllers\FormsController@store');
