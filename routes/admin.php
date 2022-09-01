<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "Admin" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::group(['namespace' => 'Admin'], function(){
  Route::middleware(['guest.admin'])->group(function () {
    Route::get('/', 'LoginController@index');
    Route::get('/', 'LoginController@index')->name('admin/showLogin');
    Route::post('/login', 'LoginController@login')->name('admin/login');
    Route::get('/forgot-password', 'ForgotPasswordController@forgotPassword')->name('admin/forgot-password');
    Route::post('/forgot-password', 'ForgotPasswordController@forgot')->name('admin/forgot'); 
    Route::get('/reset-password', 'ForgotPasswordController@showResetPasswordPage')->name('admin/showResetPasswordPage');
    Route::post('/reset-password', 'ForgotPasswordController@resetPassword')->name('admin/resetPassword');
  });

  Route::middleware(['auth.admin'])->group(function () { 
    //Support user will not have access to this routes.
    Route::middleware(['support.user'])->group(function () { 
     
      Route::get('/dashboard', 'DashboardController@index')->name('admin/dashboard');
      Route::get('/transactions', 'TransactionController@index')->name('admin/transactions');
      Route::get('/profile-transection-list', 'TransactionController@profileTransectionList')->name('admin/profile-transection-list');
      Route::get('/get-security', 'AccessController@getSecurity')->name('admin/get-security');
      Route::resource('access', 'AccessController');
      Route::resource('subscriptions', 'SubscriptionController');
      Route::get('/profile-details/{id}', 'AccountController@profileDetails')->name('admin/profile-details');

     
      Route::get('/load-user-profile', 'AccountController@loadUserProfile')->name('admin/load-user-profile');
      Route::get('/view-profile/{id}', 'ProfileController@viewProfile')->name('admin/view-profile');
      Route::get('/load-edit-profile-window', 'ProfileController@loadEditProfileWindow')->name('admin/load-edit-profile-window');
      Route::get('/load-profile-journey', 'ProfileController@loadProfileJourney')->name('admin/load-profile-journey');
      Route::get('/load-profile-media-photos', 'ProfileController@loadProfileMediaPhotos')->name('admin/load-profile-media-photos');
      Route::get('/load-profile-media-video', 'ProfileController@loadProfileMediaVideo')->name('admin/load-profile-media-video');
      Route::get('/load-profile-media-audio', 'ProfileController@loadProfileMediaAudio')->name('admin/load-profile-media-audio');
      Route::get('/load-profile-stories-article', 'ProfileController@loadProfileStoriesArticle')->name('admin/load-profile-stories-article');
      Route::get('/load-more-profile-stories-article', 'ProfileController@loadMoreProfileStoriesArticle')->name('admin/load-more-profile-stories-article');
      Route::get('/read-more-stories-article/{id}', 'ProfileController@readMoreStoriesArticle')->name('admin/read-more-stories-article');
      Route::get('/load-profile-guset-book', 'ProfileController@loadProfileGuestBook')->name('admin/load-profile-guset-book');
      Route::get('/load-gravesite-detail', 'GraveSiteController@loadGravesiteDetail')->name('admin/load-gravesite-detail');
      Route::get('/load-view-all-prayers', 'GraveSiteController@loadViewAllPrayers')->name('admin/load-view-all-prayers');
      Route::post('/upload-profile-image', 'ProfileController@uploadProfileImage')->name('admin/upload-profile-image');
      Route::post('/upload-profile-banner-image', 'ProfileController@uploadProfileBannerImage')->name('admin/upload-profile-banner-image');
      Route::post('/upload-caption-image', 'ProfileController@uploadMediaImages')->name('admin/upload-caption-image');
      Route::post('/update-media-image', 'ProfileController@updateMediaImage')->name('admin/update-media-image');
      Route::post('/upload-media-video', 'ProfileController@uploadMediaVideo')->name('admin/upload-media-video');
      Route::post('/update-media-video', 'ProfileController@updateMediaVideo')->name('admin/update-media-video');
      Route::post('/upload-profile-voice-note', 'ProfileController@uploadProfileVoiceNote')->name('admin/upload-profile-voice-note');
      Route::post('/upload-article-image', 'ProfileController@uploadArticleImage')->name('admin/upload-article-image');
      Route::post('/edit-profile', 'ProfileController@editProfile')->name('admin/edit-profile');
      Route::post('/remove-upload-media', 'ProfileController@removeUploadMedia')->name('admin/remove-upload-media');
      Route::post('/remove-article', 'ProfileController@removeArticle')->name('admin/remove-article');
      Route::get('/generate-profile-qrcode', 'ProfileController@generateQrCode')->name('admin/generate-profile-qrcode');
      
      Route::get('/remove-grave-site-photo', 'ProfileController@removeGraveSitePhoto')->name('admin/remove-grave-site-photo');
    });

    Route::get('/accounts', 'AccountController@index')->name('admin/accounts');
    Route::get('/load-edit-account', 'AccountController@loadEditAccount')->name('admin/load-edit-account');
    Route::get('/update-accout-status/{id}', 'AccountController@updateAccountStatus')->name('admin/update-accout-status');
    Route::post('/edit-accout', 'AccountController@editAccount')->name('admin/edit-accout');

    Route::get('/update-access-accout-status/{id}', 'AccessController@updateAccessAccountStatus')->name('admin/update-access-accout-status');
    Route::get('/change-password', 'LoginController@changePassword')->name('admin/change-password');
    Route::post('/change-password', 'LoginController@changePasswordPost')->name('admin/change-password');
    Route::get('/access-denied', 'AccessDeniedController@index')->name('admin/access-denied');
    Route::get('/logout', 'LoginController@logout')->name('admin/logout');
    
    Route::get('/get-graph-activity-data', 'DashboardController@getGraphActivityData')->name('admin/get-graph-activity-data');

    Route::get('/get-account-activity-data', 'DashboardController@getAccountActivityData')->name('admin/get-account-activity-data');
    Route::get('/notifications', 'NotificationController@index')->name('admin/notifications');
    Route::get('/load-notifications', 'NotificationController@loadNotification')->name('admin/load-notifications');
    Route::get('/notification-list', 'NotificationController@notificationList')->name('admin/notification-list');
    Route::get('/delete-notification', 'NotificationController@deleteNotification')->name('admin/delete-notification');
    
   

  });

  /* For downlaod QR code */
  Route::get('/downlaod-qrcode/{profile_id}', function (Request $request) {
    return downlaodQrcode($request->profile_id);
  });

}); 