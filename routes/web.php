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

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;



Route::middleware(['user.profile'])->group(function () { 
    Route::get('/guest-profile/{profile_id}', 'ProfileController@guestProfile')->name('guest-profile');
    Route::get('/guest-meta/{profile_id}', 'ProfileController@guestMeta')->name('guest-meta');
});

Route::get('/load-profile-guset-book', 'ProfileController@loadProfileGuestBook')->name('load-profile-guset-book');


Route::middleware(['guest.user'])->group(function () {
    
    Route::get('/', 'LoginController@index');
    Route::post('/login', 'LoginController@login')->name('login');
    Route::get('/forgot-password', 'LoginController@forgotPassword')->name('forgot-password');
    Route::post('/otp-verification', 'LoginController@otpVerification')->name('otp-verification');

    Route::get('/sign-up', 'LoginController@signUp')->name('sign-up');
    Route::post('/sign-up', 'LoginController@signUpCreate')->name('sign-up');
    Route::post('/resend', 'LoginController@resendOtp')->name('resend');
    Route::post('/forgot-password', 'LoginController@forgotPasswordRequest')->name('forgot-password');
    Route::post('/forgot-password-otp-verification', 'LoginController@forgotPasswordOtpVerification')->name('forgot-password-otp-verification');
    Route::post('/reset-forgot-password', 'LoginController@resetForgotPassword')->name('reset-forgot-password');
  
    /* login with facebook */
    Route::get('login-facebook/{id}', 'FacebookLoginController@redirectToProvider')->name('login-facebook');
    Route::get('login-facebook-callback', 'FacebookLoginController@handleProviderCallback')->name('login-facebook-callback');

    /* login with google */
    Route::get('login-google/{id}', 'GoogleLoginController@redirectToProvider')->name('login-google');
    Route::get('login-google-callback', 'GoogleLoginController@handleProviderCallback')->name('login-google-callback');

    /* login with apple */
    Route::get('login-apple/{id}', 'AppleLoginController@redirectToProvider')->name('login-apple');
    Route::post('login-apple-callback', 'AppleLoginController@handleProviderCallback')->name('login-apple-callback');
    

    Route::get('/guest-sign-up/{id}', 'LoginController@signUp')->name('guest-sign-up');
    Route::get('guest-login/{profile_id}', 'LoginController@index')->name('guest-login');
    Route::get('/load-sign-up', 'LoginController@loadGuestSignUp')->name('load-sign-up');
    Route::get('/load-guest-login', 'LoginController@loadGuestLogin')->name('load-guest-login');
  
});    
// Auth routes
Route::middleware(['auth.user'])->group(function () { 

    Route::get('/edit-account', 'LoginController@editAccount')->name('edit-account');
    Route::post('/edit-account-detail', 'LoginController@editAccountDetail')->name('edit-account-detail');
    Route::get('/logout', 'LoginController@logout')->name('logout');
    
    /* Route can have access without complete account */
    Route::get('/profile', 'ProfileController@index')->name('profile');
    /** Middleware used to check if profile is inactive */
    Route::middleware(['user.profile'])->group(function () { 
        Route::get('/view-profile/{profile_id}', 'ProfileController@viewProfile')->name('view-profile');
        Route::get('/load-gravesite-detail', 'GraveSiteController@loadGravesiteDetail')->name('load-gravesite-detail');
        Route::get('/load-view-all-prayers', 'GraveSiteController@loadViewAllPrayers')->name('load-view-all-prayers');
        Route::get('/load-profile-media-photos', 'ProfileController@loadProfileMediaPhotos')->name('load-profile-media-photos');
        Route::get('/load-profile-journey', 'ProfileController@loadProfileJourney')->name('load-profile-journey');
        Route::get('/load-profile-media-video', 'ProfileController@loadProfileMediaVideo')->name('load-profile-media-video');
        Route::get('/load-profile-media-audio', 'ProfileController@loadProfileMediaAudio')->name('load-profile-media-audio');
        Route::get('/load-profile-stories-article', 'ProfileController@loadProfileStoriesArticle')->name('load-profile-stories-article');
        Route::get('/load-more-profile-stories-article', 'ProfileController@loadMoreProfileStoriesArticle')->name('load-more-profile-stories-article');
        Route::get('/read-more-stories-article/{id}', 'ProfileController@readMoreStoriesArticle')->name('read-more-stories-article');
    });

    Route::get('/get-subscription-plan-price', 'SubscriptionController@getSubscriptionPlanPrice')->name('get-subscription-plan-price');
    Route::get('/load-subcription-window', 'ProfileController@loadSubcriptionWindow')->name('load-subcription-window');
 

   
 
    /* middleware used to check user account is completed or not  */
    Route::middleware(['auth.user.edit.account'])->group(function () { 
      
     
        Route::get('/change-password', 'LoginController@changePassword')->name('change-password');
        Route::post('/change-password', 'LoginController@changePasswordRequest')->name('change-password');
      
        Route::get('/transactions', 'TransactionsController@index')->name('transactions');
        Route::get('/subscription-listing', 'TransactionsController@subscriptionListing')->name('subscription-listing');
        Route::get('/subscription-detail', 'TransactionsController@subscriptionDetail')->name('subscription-detail');
        Route::get('/view-subscription-plan', 'TransactionsController@viewSubscriptionPlan')->name('view-subscription-plan');
        Route::get('/transection-listing', 'TransactionsController@transectionList')->name('transection-listing');
        Route::get('/load-manage-payment', 'TransactionsController@loadManagePayment')->name('load-manage-payment');

        Route::post('/get-subscription', 'SubscriptionController@getSubscription')->name('get-subscription');
        Route::get('/load-edit-profile-window', 'ProfileController@loadEditProfileWindow')->name('load-edit-profile-window');
        Route::post('/edit-profile', 'ProfileController@editProfile')->name('edit-profile');
        Route::get('/renew-subscription-plan', 'ProfileController@renewSubscriptionPlan')->name('renew-subscription-plan');
       
        Route::get('/payment-success', 'SubscriptionController@paymentSuccess')->name('payment-success');
        Route::get('/payment-fail', 'SubscriptionController@paymentFail')->name('payment-fail');
        Route::post('/upload-caption-image', 'ProfileController@uploadCaptionImages')->name('upload-caption-image');
        Route::post('/update-media-image', 'ProfileController@updateMediaImage')->name('update-media-image');
        Route::post('/remove-upload-media', 'ProfileController@removeUploadMedia')->name('remove-upload-media');
        Route::post('/upload-media-video', 'ProfileController@uploadMediaVideo')->name('upload-media-video');
        Route::post('/update-media-video', 'ProfileController@updateMediaVideo')->name('update-media-video');
        Route::post('/upload-article-image', 'ProfileController@uploadArticleImage')->name('upload-article-image');
        Route::post('/remove-article', 'ProfileController@removeArticle')->name('remove-article');
        Route::post('/upload-profile-image', 'ProfileController@uploadProfileImage')->name('upload-profile-image');
        Route::post('/upload-profile-banner-image', 'ProfileController@uploadProfileBannerImage')->name('upload-profile-banner-image');
        Route::post('/upload-profile-voice-note', 'ProfileController@uploadProfileVoiceNote')->name('upload-profile-voice-note');

        Route::get('/remove-grave-site-photo', 'ProfileController@removeGraveSitePhoto')->name('remove-grave-site-photo');

        Route::get('/generate-profile-qrcode', 'ProfileController@generateQrCode')->name('generate-profile-qrcode');
        
       
        Route::get('/cancel-subscription', 'SubscriptionController@cancelSubscription')->name('cancel-subscription');
        Route::post('/switch-subscription', 'SubscriptionController@switchSubscription')->name('switch-subscription');
        Route::get('/checkout-detail/{planId}/{subscriptionId}/{type}', 'SubscriptionController@checkoutDetail')->name('checkout-detail');
        Route::post('/buy-subscription', 'SubscriptionController@buySubscription')->name('buy-subscription');
        Route::post('/add-card', 'SubscriptionController@addCard')->name('add-card');
        Route::get('/make-card-default', 'SubscriptionController@makeCardDefault')->name('make-card-default');
        Route::get('/delete-card', 'SubscriptionController@deleteCard')->name('delete-card');
        
        /** Middleware used to check if profile is inactive */
        Route::middleware(['user.profile'])->group(function () { 
            Route::get('/voice-recording-model', 'ProfileController@voiceRecordingModel')->name('voice-recording-model');
            Route::post('/upload-record-voice-note', 'ProfileController@uploadProfileVoiceNote')->name('upload-record-voice-note');
            
            /* Family tree module */
            Route::get('/family-tree/{profile_id}', 'FamilyTreeController@index')->name('family-tree');
            
            Route::post('/save-family-tree', 'FamilyTreeController@saveFamilyTree')->name('save-family-tree');
            Route::post('/upload-member-image', 'FamilyTreeController@uploadMemberImage')->name('upload-member-image');

        });
       
        
    });

    /* For notifications */
    Route::get('/load-notifications', 'NotificationController@index')->name('load-notifications');
    Route::get('/delete-notification', 'NotificationController@deleteNotification')->name('delete-notification');
   
});

Route::get('/guest-family-tree/{profile_id}', 'FamilyTreeController@index')->name('guest-family-tree');
Route::get('/get-family-tree', 'FamilyTreeController@getFamilyTree')->name('get-family-tree');

Route::post('/stripe-webhooks', 'WebhookController@stripeWebhooks')->name('stripe-webhooks');

Route::post('/apple-webhooks', 'WebhookController@appleWebhooks')->name('apple-webhooks');


/* For downlaod QR code */
Route::get('/downlaod-qrcode/{profile_id}', function (Request $request) {
    return downlaodQrcode($request->profile_id);
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
Route::get('/clear-config', function () {
    Artisan::call('config:clear');
    return "Config is cleared";
});
Route::get('/config-cache', function () {
    Artisan::call('config:cache');
    return "Config Cache is cleared";
});
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return "Storage link created";
});
