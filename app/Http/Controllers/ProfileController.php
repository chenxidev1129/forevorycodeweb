<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProfileRepository;
use App\Repositories\SubscriptionPlanRepository;
use App\Repositories\ProfileSubscriptionRepository;
use App\Repositories\ProfileMediaRepository;
use App\Repositories\ProfileStoriesArticleRepository;
use App\Repositories\ProfileGraveSiteRepository;
use App\Http\Requests\EditProfileRequest;
use App\Repositories\UserCardRepository;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class ProfileController extends Controller
{

    /**
     * Function to show profile.
     * @return \Illuminate\Http\Response 
     */
    public function index(Request $request){
        /* Get All profiles */
        $profiles = ProfileRepository::getAllProfiles();
        /* Get login user signed book */
        $getUserSignedBook = ProfileMediaRepository::getUserSignedBook($request);
        return view('user.profile',compact('profiles','getUserSignedBook'));
    }

    public function guestMeta(Request $request, $profileId){
        $getProfile = ProfileRepository::findOne(['id' => $profileId]);
        return view('user.layouts.meta', compact('getProfile'));
    }

    /**
     * Function to show profile view.
     * @return \Illuminate\Http\Response 
     */
    public function viewProfile(Request $request,$profileId){
        $getLoginUser = getUserDetail();
        /* check for existing profile */
        $getProfile = ProfileRepository::findOne(['id' => $profileId], 'ProfileSubscription');
        if(empty($getProfile)) {
            if($profileId != 0){
                return redirect('view-profile/0');
            }  
        }

        $profileStatus = ProfileSubscriptionRepository::getSubscriptionStatus($profileId);
        /* Check profile if not belongs to the login user */
      
        if(!empty($getProfile) && $profileStatus != 'active' && $getProfile->user_id != $getLoginUser->id){
            return redirect('profile');   
        }
        return view('user.profile.view-profile', compact('profileStatus','profileId', 'getProfile'));
    }    
          
    /**
     * Function to check and get subcription window.
     * @return \Illuminate\Http\Response 
     */
    public function loadSubcriptionWindow(){
        try {
          
            $free_trial = SubscriptionPlanRepository::findOne(['slug'=>'free_trial']);
            $subscriptionPlan = SubscriptionPlanRepository::getSubscriptionPlan(['status'=>'active'], ['free_trial']);
            $userSaveCard = UserCardRepository::getSaveCard();
            $view = View::make('user.profile.start-free-trial-sidebar', compact('free_trial', 'subscriptionPlan', 'userSaveCard'));

            return  response()->json(
                [
                    'success' => true,
                    'data' => $view->render(),
                    'message' => ''
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'data' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }    

    /**
     * Function to check and get subcription window.
     * @return \Illuminate\Http\Response 
     */
    public function loadEditProfileWindow(Request $request){
        try {

            $getMedia = ProfileRepository::findOne(['id' => $request->profile_id] , ['profileMediaImage','profileMediaVideo', 'profileMediaAudio.user', 'ProfileStoriesArticle', 'ProfileGraveSite']);
            $profile_id = $request->profile_id;
            $view = View::make('user.profile.edit-profile-sidebar',compact('getMedia','profile_id'));

            return  response()->json(
                [
                    'success' => true,
                    'data' => $view->render(),
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }  
    
    /**
     * Function used to add file
     * @return \Illuminate\Http\Response 
     */
    public function uploadCaptionImages(Request $request){
        try {
            $id = ProfileMediaRepository::uploadMediaImages($request);
            return  response()->json(
                [
                    'success' => true,
                    'id' => $id,
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Function used to update image
     * @return \Illuminate\Http\Response 
     */
    public function updateMediaImage(Request $request){
        try {
            $getUpdatedImage = ProfileMediaRepository::updateMediaImage($request);
            return  response()->json(
                [
                    'success' => true,
                    'data' => $getUpdatedImage,
                    'message' => __('message.update_media_image_success')
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'data' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Function used to remove file
     * @return \Illuminate\Http\Response 
     */
    public function removeUploadMedia(Request $request){
        try {
            ProfileMediaRepository::removeUploadMedia($request, 'profile');
            return  response()->json(
                [
                    'success' => true,
                    'message' => __('message.media_file_removed_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Function used to add file
     * @param Request $request
     * @return id
     */
    public function uploadMediaVideo(Request $request){
        try {
            $data = ProfileMediaRepository::uploadMediaVideo($request);
            return  response()->json(
                [
                    'success' => true,
                    'data' => $data,
                    'message' => ''
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'data' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    
    /**
     * Function used to update media video
     * @param Request $request
     * @return id
     */
    public function updateMediaVideo(Request $request){
        try {
            $getUpdateVideo = ProfileMediaRepository::updateMediaVideo($request);
            return  response()->json(
                [
                    'success' => true,
                    'data' => $getUpdateVideo,
                    'message' => __('message.profile_media_video_update_success')
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'data' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Function used to upload article image.
     * @param Request $request
     * @return id
     */
    public function uploadArticleImage(Request $request){
        try {
            $id = ProfileStoriesArticleRepository::uploadArticleImages($request);
            return  response()->json(
                [
                    'success' => true,
                    'id' => $id,
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    
    /**
     * Function used to remove article
     * @param Request $request
     * @return \Illuminate\Http\Response 
     */
    public function removeArticle(Request $request){
        try {
            ProfileStoriesArticleRepository::removeArticle($request, 'profile');
            return  response()->json(
                [
                    'success' => true,
                    'message' => __('message.stories_article_removed_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    
    /**
     * Function used to update subscription profile
     * @param EditProfileRequest $request
     * @return \Illuminate\Http\Response 
     */
    public function editProfile(EditProfileRequest $request){
        try {
            ProfileRepository::editProfile($request);
            return  response()->json(
                [
                    'success' => true,
                    'message' => __('message.profile_update_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Function used to upload profile image. 
     * @param Request $request
     * @return \Illuminate\Http\Response 
     */
    public function uploadProfileImage(Request $request){
        try {
            ProfileRepository::uploadProfileImage($request);
            return  response()->json(
                [
                    'success' => true,
                    'message' => __('message.profile_image_updated_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Function used to upload profile banner image. 
     * @param Request $request
     * @return \Illuminate\Http\Response 
     */
    public function uploadProfileBannerImage(Request $request){
        try {
            ProfileRepository::uploadProfileBannerImage($request);
            return  response()->json(
                [
                    'success' => true,
                    'message' => __('message.profile_backgroud_image_updated_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Function used to upload profile voice note. 
     * @param Request $request
     * @return \Illuminate\Http\Response 
     */
    public function uploadProfileVoiceNote(Request $request){
        try {
            $voiceNote = ProfileMediaRepository::uploadProfileVoiceNote($request);
            return  response()->json(
                [
                    'success' => true,
                    'data' => $voiceNote,
                    'message' => __('message.profile_voice_note_upload_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
  
    /**
     * Function used to remove grave site photo. 
     * @param Request $request
     * @return \Illuminate\Http\Response 
     */
    public function removeGraveSitePhoto(Request $request){
        try {
            ProfileGraveSiteRepository::removeGraveSitePhoto($request);
            return  response()->json(
                [
                    'success' => true,
                    'message' => __('message.grave_site_image_removed_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }    

    /**
     * Function used to generate QR code. 
     * @param Request $request
     * @return \Illuminate\Http\Response 
     */
    public function generateQrCode(Request $request){
        try {
            
            $result = ProfileRepository::generateProfileQrCode($request);

            if($result){
                $view = View::make('user.profile.generate-qrcode',compact('result'));
                return  response()->json(
                    [
                        'success' => true,
                        'data' => $view->render(),
                        'message' => ''
                    ],
                    Response::HTTP_OK
                );
            } else {
                return  response()->json(
                    [
                        'success' => false,
                        'data' => [],
                        'message' => __('message.qr_code_not_generated')
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }   
    
    /**
     * Function used to load media photos in detail page
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function loadProfileMediaPhotos(Request $request){
        try {
            $getProfileDetail = ProfileRepository::getProfileMediaPhotos($request);
            $html = View::make('user.profile.profile-media-photos', compact('getProfileDetail'))->render();
            return response()->json(
                [
                    'success' => true,
                    'html' => $html
                ],
                Response::HTTP_OK
            );
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'html' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }  
    
    /**
     * Function used to load media photos in detail page
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function loadProfileJourney(Request $request){
        try {
            $getProfileJourney = ProfileRepository::getProfile($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getProfileJourney
                ],
                Response::HTTP_OK
            ); 
			} catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
					 'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }  
       
 
    /**
     * Function to show guest profile.
     * @return \Illuminate\Http\Response 
     */
    public function guestProfile(Request $request,$profileId){
        /* check for existing profile */
        $getProfile = ProfileRepository::getGuestProfileWithUser($profileId);
        $profileStatus = ProfileSubscriptionRepository::getSubscriptionStatus($profileId);

        if($profileStatus == 'active') {
            if (Auth::guard('user-web')->check()) {
                return redirect('view-profile/'.$profileId);
            } 
            return view('user.profile.guest-profile', compact('profileStatus','profileId', 'getProfile'));
        } else {
            return redirect('/');
        }
    }

    /**
     * Function used to load profile media video
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function loadProfileMediaVideo(Request $request){
        try {
            $getProfileDetail = ProfileRepository::getProfileMediaVideo($request);
            $html = View::make('user.profile.profile-media-video', compact('getProfileDetail'))->render();
            return response()->json(
                [
                    'success' => true,
                    'html' => $html
                ],
                Response::HTTP_OK
            );
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'html' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }  
    
    /**
     * Function used to load profile media audio
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function loadProfileMediaAudio(Request $request){
        try {
            $getProfileDetail = ProfileMediaRepository::getProfileMediaAudio($request);
            
            $html = (!empty($getProfileDetail) && count($getProfileDetail)) ? View::make('user.profile.profile-media-audio', compact('getProfileDetail'))->render() : '';

            return response()->json(
                [
                    'success' => true,
                    'last_page' => $getProfileDetail->lastPage(),
                    'html' => $html
                ],
                Response::HTTP_OK
            );
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'html' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    } 

    /**
     * Function used to load profile stories article
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function loadProfileStoriesArticle(Request $request){
        try {
            $getProfileDetail = ProfileStoriesArticleRepository::getProfileStoriesArticle($request);
            $html = (!empty($getProfileDetail) && count($getProfileDetail) > 0) ? View::make('user.profile.profile-stories-article', compact('getProfileDetail'))->render() : '';

            return response()->json(
                [
                    'success' => true,
                    'last_page' => $getProfileDetail->lastPage(),
                    'html' => $html
                ],
                Response::HTTP_OK
            );
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'html' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Function used to load load more stories
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function loadMoreProfileStoriesArticle(Request $request){
        try {
            $getProfileDetail = ProfileStoriesArticleRepository::getProfileStoriesArticle($request);
            $html = View::make('user.profile.load-more-profile-stories-article', compact('getProfileDetail'))->render();
            return response()->json(
                [
                    'success' => true,
                    'last_page' => $getProfileDetail->lastPage(),
                    'html' => $html
                ],
                Response::HTTP_OK
            );
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'html' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    
    /**
     * Function used to load read more stories article
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function readMoreStoriesArticle(Request $request){
        $getStoriesArticle = ProfileStoriesArticleRepository::findOne(['id' => $request->id]);
        return view('user.profile.read-more-stories-article', compact('getStoriesArticle'));
    }   

    /**
     * Function used to load profile guest book
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function loadProfileGuestBook(Request $request){
        try {
            $getProfileGuestBook = ProfileMediaRepository::getProfileGuestBook($request);
         
            $html = (!empty($getProfileGuestBook) && count($getProfileGuestBook) > 0) ? View::make('user.profile.profile-guest-book', compact('getProfileGuestBook'))->render() : '';
           
            return response()->json(
                [
                    'success' => true,
                    'last_page' => $getProfileGuestBook->lastPage(),
                    'html' => $html
                ],
                Response::HTTP_OK
            );
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'html' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    } 

    /**
     * Function used to load voice note model
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function voiceRecordingModel(Request $request){
        try {
            
            $html = View::make('user.profile.voice-recording-model')->render();
            return response()->json(
                [
                    'success' => true,
                    'html' => $html
                ],
                Response::HTTP_OK
            ); 
			} catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
					'html' => '',
					 'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }  

    /**
     * Load renew subscription plan model.
     * @return \Illuminate\Http\Response
     */
    public function renewSubscriptionPlan(Request $request)
    {
        $getCurrentSubscriptionDetail = ProfileSubscriptionRepository::findOne(['id'=> $request->id], ['subscription']);
        $getAllPlan = SubscriptionPlanRepository::getSubscriptionPlan(['status'=> 'active'], ['free_trial']);        
        $html = View::make('user.profile.load-renew-subscription-plan', compact('getAllPlan','getCurrentSubscriptionDetail'))->render();
        return response()->json(
            [
                'success' => true,
                'html' => $html
            ],
            Response::HTTP_OK
        );
    }
}
