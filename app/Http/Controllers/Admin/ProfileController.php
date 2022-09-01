<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProfileRepository;
use App\Repositories\ProfileSubscriptionRepository;
use App\Repositories\ProfileMediaRepository;
use App\Repositories\ProfileStoriesArticleRepository;
use App\Repositories\ProfileGraveSiteRepository;
use App\Http\Requests\EditProfileRequest;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;
use Exception;
class ProfileController extends Controller
{
    
    /**
     * Show profile. 
     * @return view 
     */
    public function viewProfile(Request $request,$profileId){
        /* check for existing profile */
        $getProfile = ProfileRepository::findOne(['id' => $profileId]);
        $profileStatus = ProfileSubscriptionRepository::getSubscriptionStatus($profileId);
         return view('admin.accounts.profile.view-profile', compact('profileStatus','profileId', 'getProfile'));
      
    }
      
    /**
     * Function used to load edit profile window.
     * @return \Illuminate\Http\Response 
     */
    public function loadEditProfileWindow(Request $request){
        try {

            $getMedia = ProfileRepository::findOne(['id' => $request->profile_id] , ['profileMediaImage','profileMediaVideo', 'profileMediaAudio.user', 'ProfileStoriesArticle', 'ProfileGraveSite']);
            $profile_id = $request->profile_id;
            $view = View::make('admin.accounts.profile.edit-profile-sidebar',compact('getMedia','profile_id'));

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
     * Function used to load profile journey
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
     * Function used to load media photos in detail page
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function loadProfileMediaPhotos(Request $request){
        try {
            $getProfileDetail = ProfileRepository::getProfileMediaPhotos($request);
            $html = View::make('admin.accounts.profile.profile-media-photos', compact('getProfileDetail'))->render();
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
     * Function used to load profile media video
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function loadProfileMediaVideo(Request $request){
        try {
            $getProfileDetail = ProfileRepository::getProfileMediaVideo($request);
            $html = View::make('admin.accounts.profile.profile-media-video', compact('getProfileDetail'))->render();
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
            $html = '';
            if(!empty($getProfileDetail) && count($getProfileDetail)){
                $html = View::make('admin.accounts.profile.profile-media-audio', compact('getProfileDetail'))->render();
            }
            
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
            $html = '';
            if(!empty($getProfileDetail) && count($getProfileDetail) > 0){
                $html = View::make('admin.accounts.profile.profile-stories-article', compact('getProfileDetail'))->render();
            }
            
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
            $html = View::make('admin.accounts.profile.load-more-profile-stories-article', compact('getProfileDetail'))->render();
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
     * Function used to load read more stories article.
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function readMoreStoriesArticle(Request $request){
        $getStoriesArticle = ProfileStoriesArticleRepository::findOne(['id' => $request->id]);
        return view('admin.accounts.profile.read-more-stories-article', compact('getStoriesArticle'));
    }      
    
    /**
     * Function used to load profile guset book.
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function loadProfileGuestBook(Request $request){
        try {
            $getProfileGuestBook = ProfileMediaRepository::getProfileGuestBook($request);
        
            $html = '';
            if(!empty($getProfileGuestBook) && count($getProfileGuestBook) > 0){
                $html = View::make('admin.accounts.profile.profile-guest-book', compact('getProfileGuestBook'))->render();
            }
            
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
     * Function used to upload profile media image.
     * @return \Illuminate\Http\Response 
     */
    public function uploadMediaImages(Request $request){
        try {
            $id = ProfileMediaRepository::uploadMediaImages($request);
            return  response()->json(
                [
                    'success' => true,
                    'id' => $id,
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
     * Function used to update profile media image
     * @return \Illuminate\Http\Response 
     */
    public function updateMediaImage(Request $request){
        try {
            $getUpdatedImage = ProfileMediaRepository::updateMediaImage($request);
            return  response()->json(
                [
                    'success' => true,
                    'data' => $getUpdatedImage,
                    'message' => 'Media image updated successfully'
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
     * Function used to remove profile media.
     * @return \Illuminate\Http\Response 
     */
    public function removeUploadMedia(Request $request){
        try {
            ProfileMediaRepository::removeUploadMedia($request, 'profile');
            return  response()->json(
                [
                    'success' => true,
                    'message' => 'Removed successfully'
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
     * Function used upload media video files.
     * @param Request $request
     * @return data
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
     * Function used to update media video.
     * @param Request $request
     * @return getUpdateVideo
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
     * Function used to remove article.
     * @param Request $request
     * @return \Illuminate\Http\Response 
     */
    public function removeArticle(Request $request){
        try {
            ProfileStoriesArticleRepository::removeArticle($request, 'profile');
            return  response()->json(
                [
                    'success' => true,
                    'message' => 'Stories & Articles removed successfully'
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
     * Function used to update profile detail.
     * @param EditProfileRequest $request
     * @return \Illuminate\Http\Response 
     */
    public function editProfile(EditProfileRequest $request){
        try {
            
            ProfileRepository::editProfile($request);
            
            return  response()->json(
                [
                    'success' => true,
                    'message' => 'Profile updated successfully'
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
                $view = View::make('admin.accounts.profile.generate-qrcode',compact('result'));
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
                        'message' => __('message.something_went_wrong_while_creating_qr_code')
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
   
}
