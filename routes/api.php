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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api'], function () {

    Route::post('/login', 'LoginController@login')->name('login');
    Route::post('/sign-up', 'LoginController@signUp')->name('sign-up');
    Route::post('/otp-verification', 'LoginController@otpVerification')->name('otp-verification');
    Route::post('/social-login', 'LoginController@socialLogin')->name('social-login');
    Route::post('/forgot-password', 'LoginController@forgotPassword')->name('forgot-password');
    Route::post('/forgot-password-otp-verification', 'LoginController@forgotPasswordOtpVerification')->name('forgot-password-otp-verification');
    Route::post('/reset-forgot-password', 'LoginController@resetForgotPassword')->name('reset-forgot-password');
    Route::post('/resend-otp', 'LoginController@resendOtp')->name('resend-otp');
    Route::get('/get-default-profile-data', 'ProfileController@getDefaultProfileData');
    Route::get('/get-guest-profile/{profileId}', 'ProfileController@getGuestProfile');
  
    Route::group(['middleware' => ['auth.jwt']], function () {
        Route::post('/edit-account', 'AccountController@editAccount');
        Route::post('/logout', 'AccountController@logout');
        Route::post('/change-password', 'AccountController@changePasswordRequest');
        Route::get('/get-setting', 'AccountController@getSetting');
       
        Route::get('/get-subscription-plan', 'SubscriptionController@getSubscriptionPlan');
        Route::post('/subscription-checkout', 'SubscriptionController@subscriptionCheckout');
        Route::put('/edit-profile-detail/{profileId}', 'ProfileController@editProfileDetail');
        Route::get('/get-profile-list', 'ProfileController@getProfileList');
        Route::get('/get-profile/{profileId}', 'ProfileController@getProfile');
        Route::post('/upload-profile-cover-image', 'ProfileController@uploadProfileCoverImage');
        Route::put('/edit-profile-journey/{profileId}', 'ProfileController@editProfileJourney');
        Route::post('/upload-media-image', 'ProfileController@uploadMediaImage');
        Route::post('/update-media-image', 'ProfileController@updateMediaImage');
        Route::delete('/delete-media', 'ProfileController@deleteMedia');
        Route::put('/update-media-position/{profileId}', 'ProfileController@updateMediaPosition');
        Route::post('/upload-media-video', 'ProfileController@uploadMediaVideo');
        Route::post('/update-media-video', 'ProfileController@updateMediaVideo');
        Route::post('/upload-voice-note', 'ProfileController@uploadVoiceNote');
        Route::post('/add-stories-articles', 'ProfileController@addStoriesArticles');
        Route::post('/update-stories-articles-media', 'ProfileController@updateStoriesArticlesMedia');
        Route::put('/update-stories-articles-position/{profileId}', 'ProfileController@updateStoriesArticlesPosition');
        Route::delete('/delete-stories-articles', 'ProfileController@deleteStoriesArticle');
        Route::post('/add-update-grave-site-image', 'ProfileController@addUpdateGraveSiteImage');
        Route::delete('/delete-grave-site-image', 'ProfileController@deleteGraveSiteImage');
        Route::post('/add-grave-site-location', 'ProfileController@addUpdateGraveSiteLocation');
        Route::put('/update-grave-location/{profileId}', 'ProfileController@updateGraveSiteLocation');
        Route::post('/add-card', 'PaymentMethodController@addCard');
        Route::get('/get-save-card', 'PaymentMethodController@getCard');
        Route::delete('/delete-card', 'PaymentMethodController@deleteCard');
        Route::post('/make-card-default', 'PaymentMethodController@makeCardDefault');
        Route::get('/get-transactions', 'SubscriptionController@getTransactions');
        Route::get('/get-subscriptions', 'SubscriptionController@getSubscription');
        Route::get('/get-switch-plan', 'SubscriptionController@getSwitchPlan');
        Route::get('/get-buy-now-plan', 'SubscriptionController@getBuyNowPlan');
        Route::post('/cancel-subscription', 'SubscriptionController@cancelSubscription');
        Route::post('/switch-subscription', 'SubscriptionController@switchSubscription');
        Route::post('/buy-subscription', 'SubscriptionController@buySubscription');
        Route::get('/get-profile-media', 'ProfileController@getProfileMedia');
        Route::get('/get-profile-stories_articles', 'ProfileController@getProfileStoriesArticles');
        Route::get('/get-profile-guest-book', 'ProfileController@getProfileGuestBook');
        Route::get('/guest-book-you-signed', 'ProfileController@getSignedUserGuestBook');
        Route::get('/get-account-detail', 'AccountController@getAccountDetail');
      
        Route::get('/notifications', 'NotificationController@getNotifications');
        Route::delete('/dismiss-notification/{notificationId}', 'NotificationController@dismissNotification');

        
        
    });

    Route::post('/save-transaction-detail', 'SubscriptionController@saveTransactionDetail');
}); 