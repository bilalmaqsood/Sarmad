<?php

/**
 * Public Routes
 * -----------------------------------------------------------
 */


// Login is the first page a new visitor should see
Route::get('/', function () { return redirect('login'); });

Route::get('/', function () {
    return redirect('login');
});


// The Public Tour Route
Route::get('/public/{domain}/{tourId}', 'PublicController@viewTour');
// Legal & Misc
Route::get('/guide', 'PublicPageController@guideIndex');
Route::get('/legal/terms-and-conditions', 'PublicPageController@termsIndex');
Route::get('/legal/privacy-policy', 'PublicPageController@privacyIndex');
Route::get('/legal/dmca', 'PublicPageController@dmcaIndex');


// Public Sbscription

Route::get('/subscription/', 'PublicPageController@subscriptionUpgradeIndex');

// Splashes
Route::get('/deactivation-confirmation', 'PublicPageController@deactivationIndex')->name('deactivation-confirmation');
Route::get('/no-js', 'PublicPageController@noJs');
Route::get('/not-compatible', function (){ return view('errors.browser-compability'); });



/**
 * Private Routes protected by Auth Facade
 * ----------------------------------------------------------
 */		Auth::routes();

// User verification
Route::get('/user/verify-account', 'UsersController@verifyIndex');
Route::post('/user/verify-account', 'UsersController@ajaxVerify');
Route::post('/user/verify-account-code', 'UsersController@ajaxVerifyCode');
Route::post('/user/verify-account/redirect', 'UsersController@verifyRedirect');


// User reactivation
Route::get('/user/reactivation', 'UsersController@reactivateIndex');
Route::post('/user/reactivation', 'UsersController@reactivateAccount');


// Routes protected by various account & subscription related middleware
Route::group(
	['middleware' => [
		'account.activated', 'account.verified', 'subscription.paid']
	],

	function () {

	/**
	 * Tours
	 */
	Route::get('/tours', 'ToursController@index')->name('tours');
	Route::get('/tours/search', 'ToursController@searchResults');
	Route::get('/tours/new', 'UploadController@uploadIndex');

	Route::post('/tours/new', 'UploadController@ajaxMakeTour');
	Route::post('/tours/new/store-image', 'UploadController@ajaxStoreImage');
	
	Route::post('/tours/new/redirect', 'UploadController@uploadSuccessRedirect');

	Route::get('/tours/new/redirect', 'UploadController@uploadSuccessRedirect');
	
	
	Route::get('/tours/{tourId}/make-public', 'ToursController@makeTourPublic')->middleware('subscription.allowance');
    Route::get('/tours/{tourId}', 'PublicPageController@viewPublicTour');
	Route::get('/tours/{tourId}/make-private', 'ToursController@makeTourPrivate');
	Route::get('/tours/{tourId}/delete', 'ToursController@deleteTour');

	/**
	 * User
	 */
	Route::get('/user/settings', 'UsersController@settingsIndex')->name('settings');
	Route::post('/user/settings/{setting}', 'UsersController@settingsUpdate');

	Route::get('/user/billing', 'UsersController@billingIndex')->name('billing');
	Route::get('/user/subscription', 'SubscriptionController@subscriptionIndex')->name('subscription');
	Route::get('/user/subscription/cancel', 'SubscriptionController@subscriptionCancelIndex');
	Route::post('/user/subscription/cancel', 'SubscriptionController@subscriptionCancel');
	Route::get('/user/subscription/upgrade', 'SubscriptionController@subscriptionUpgradeIndex');
	Route::post('/user/subscription/upgrade', 'SubscriptionController@subscriptionUpgrade');
	Route::get('/user/subscription/order', 'SubscriptionController@subscriptionOrderIndex');
	Route::post('/user/subscription/order', 'SubscriptionController@newSubscriptionRequest');
	Route::get('/order-confirmation', 'SubscriptionController@orderConfirmation');
	Route::get('/upgrade-subscription', 'SubscriptionController@upgradeSplash')->name('upgrade-subscription');

	Route::get('/user/deactivate-account', 'UsersController@deactivateIndex');
	Route::post('/user/deactivate-account', 'UsersController@deactivateAccount');

	/**
	 * Misc routes, small pages, redirects etc
	 */

	Route::get('/support', 'PublicPageController@supportIndex');

	Route::get('/', function () {
	    return redirect('tours');
	});

	Route::get('/home', function () {
	    return redirect('tours');
	});


	/**
	 * Dev Routes
	 * --------------------------------------------------------
	 */

	if (getenv('APP_ENV') != 'production') {

		Route::get('/populate-tours', 'ToursController@tempPopulateTours')->name('populate-tours');
		Route::get('/tours/viewstatic', 'ViewerController@viewerIndex');

	}

});