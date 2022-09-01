<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProfileRepository;
use App\Repositories\ProfileMediaRepository;
use App\Repositories\DefaultProfileRepository;
use App\Http\Requests\Api\ProfileDetailRequest;
use App\Http\Requests\Api\ProfileImageRequest;
use App\Http\Requests\Api\ProfileJourneyRequest;
use App\Http\Requests\Api\UploadMediaImageRequest;
use App\Http\Requests\Api\UpdateMediaImageRequest;
use App\Http\Requests\Api\UploadMediaVideoRequest;
use App\Http\Requests\Api\UpdateMediaVideoRequest;
use App\Http\Requests\Api\UploadMediaVoiceNoteRequest;
use App\Http\Requests\Api\AddStoriesArticlesRequest;
use App\Http\Requests\Api\UpdateStoriesArticleMediaRequest;
use App\Http\Requests\Api\AddGraveSiteLocationRequest;
use App\Http\Requests\Api\AddUpdateGraveImageRequest;
use App\Repositories\ProfileGraveSiteRepository;
use App\Repositories\ProfileStoriesArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class ProfileController extends Controller
{
    /**
    * Function used to edit profile detail
    * @param ProfileDetailRequest  
    * @return \Illuminate\Http\JsonResponse
    */
    
    public function editProfileDetail(ProfileDetailRequest $request){
        try {

            $qrCode = ProfileRepository::editProfileDetail($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $qrCode,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used to upload profile image.
    * @param ProfileImageRequest  
    * @return \Illuminate\Http\JsonResponse
    */
    
    public function uploadProfileCoverImage(ProfileImageRequest $request){
        try {

            ProfileRepository::uploadProfileCoverImage($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used to get profile.
    * @param Request  
    * @return \Illuminate\Http\JsonResponse
    */
    
    public function getProfile(Request $request){
        try {

            $getProfile = ProfileRepository::getProfileDetail($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getProfile,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'data' => "",
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    } 

   /**
    * Function used to get user profile list.
    * @param Request  
    * @return \Illuminate\Http\JsonResponse
    */
    
    public function getProfileList(Request $request){
        try {

            $getProfileList = ProfileRepository::getAllProfiles();
            return response()->json(
                [
                    'success' => true,
                    'data' => $getProfileList,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'data' => "",
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    } 

   /**
    * Function used to edit profile journey.
    * @param ProfileJourneyRequest  
    * @return \Illuminate\Http\JsonResponse
    */
    
    public function editProfileJourney(ProfileJourneyRequest $request){
        try {
            
            ProfileRepository::editProfileJourney($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * @param UploadMediaImageRequest  
    * @return \Illuminate\Http\JsonResponse
    */
    
    public function uploadMediaImage(UploadMediaImageRequest $request){
        try {

            $id = ProfileMediaRepository::uploadMediaImages($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => array('id' => $id),
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used to update profile media image.
    * @param UpdateMediaImageRequest  
    * @return \Illuminate\Http\JsonResponse
    */
    
    public function updateMediaImage(UpdateMediaImageRequest $request){
        try {
            
            ProfileMediaRepository::updateMediaImage($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used to delete media.
    * @param Request  
    * @return \Illuminate\Http\JsonResponse
    */
    
    public function deleteMedia(Request $request){
        try {
            
            ProfileMediaRepository::removeUploadMedia($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.profile_media_delete_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used to update profile media image.
    * @param UpdateMediaImageRequest  
    * @return \Illuminate\Http\JsonResponse
    */
    
    public function updateMediaPosition(Request $request){
        try {
            
            ProfileMediaRepository::updateMediaPosition($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used to upload profile media video.
    * @param UploadMediaVideoRequest  
    * @return \Illuminate\Http\JsonResponse
    */
    
    public function uploadMediaVideo(UploadMediaVideoRequest $request){
        try {
            
            $mediaVideoDetail =  ProfileMediaRepository::uploadMediaVideo($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $mediaVideoDetail,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used to update profile media video.
    * @param UpdateMediaVideoRequest  
    * @return \Illuminate\Http\JsonResponse
    */
    
    public function updateMediaVideo(UpdateMediaVideoRequest $request){
        try {
           
            ProfileMediaRepository::updateMediaVideo($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.profile_media_video_update_success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used to upload voice note
    * @param UploadMediaVoiceNoteRequest  
    * @return \Illuminate\Http\JsonResponse
    */
    
    public function uploadVoiceNote(UploadMediaVoiceNoteRequest $request){
        try {
            
            $voiceNoteDetail = ProfileMediaRepository::uploadProfileVoiceNote($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $voiceNoteDetail,
                    'message' => __('message.profile_voice_note_upload_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used to add profile stories and article.
    * @param AddStoriesArticlesRequest  
    * @return \Illuminate\Http\JsonResponse
    */
    
    public function addStoriesArticles(AddStoriesArticlesRequest $request){
        try {
            
            ProfileStoriesArticleRepository::profileStoriesArticles($request, 'add');
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used to update profile stories and article. 
    * @param UpdateStoriesArticleMediaRequest  
    * @return \Illuminate\Http\JsonResponse
    */

    public function updateStoriesArticlesMedia(UpdateStoriesArticleMediaRequest $request){
        try {
            
            ProfileStoriesArticleRepository::profileStoriesArticles($request, 'update');
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used to delete profile stories and article. 
    * @param Request  
    * @return \Illuminate\Http\JsonResponse
    */

    public function deleteStoriesArticle(Request $request){
        try {
            
            ProfileStoriesArticleRepository::removeArticle($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.profile_stories_articles_deleted_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used update stories and article position. 
    * @param Request  
    * @return \Illuminate\Http\JsonResponse
    */

    public function updateStoriesArticlesPosition(Request $request){
        try {
            
            ProfileStoriesArticleRepository::updateStoriesArticlesPosition($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used add update grave site image. 
    * @param Request  
    * @return \Illuminate\Http\JsonResponse
    */

    public function addUpdateGraveSiteImage(AddUpdateGraveImageRequest $request){
        try {
            
            ProfileGraveSiteRepository::addUpdateGraveSiteImage($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used add grave site location. 
    * @param AddGraveSiteLocationRequest  
    * @return \Illuminate\Http\JsonResponse
    */

    public function addUpdateGraveSiteLocation(AddGraveSiteLocationRequest $request){
        try {
            
            ProfileGraveSiteRepository::addGraveSiteDetails($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.grave_site_location_added_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used add grave site location. 
    * @param AddGraveSiteLocationRequest  
    * @return \Illuminate\Http\JsonResponse
    */

    public function updateGraveSiteLocation(AddGraveSiteLocationRequest $request){
        try {
            
            ProfileGraveSiteRepository::addGraveSiteDetails($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.grave_site_location_updated_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used to delete grave site image. 
    * @param Request  
    * @return \Illuminate\Http\JsonResponse
    */

    public function deleteGraveSiteImage(Request $request){
        try {
            
            ProfileGraveSiteRepository::removeGraveSitePhoto($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.grave_site_image_removed_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function is used to get profile media.
    * @param Request  
    * @return \Illuminate\Http\JsonResponse
    */

    public function getProfileMedia(Request $request){
        try {
            
            $getMedia = ProfileMediaRepository::getProfileMedia($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getMedia,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function is used to get profile stories and articles.
    * @param Request  
    * @return \Illuminate\Http\JsonResponse
    */

    public function getProfileStoriesArticles(Request $request){
        try {
            
            $getProfileStoriesArticleList = ProfileStoriesArticleRepository::getProfileStoriesArticleList($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getProfileStoriesArticleList,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function is used to get profile guest book.
    * @param Request  
    * @return \Illuminate\Http\JsonResponse
    */

    public function getProfileGuestBook(Request $request){
        try {
            
            $getProfileGuestBook = ProfileMediaRepository::getProfileGuestBookList($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getProfileGuestBook,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function is used to get user signed guest book.
    * @param Request  
    * @return \Illuminate\Http\JsonResponse
    */
    
    public function getSignedUserGuestBook(Request $request){
        try {
            
            $getUserSignedBookList = ProfileMediaRepository::getUserSignedBookList($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getUserSignedBookList,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function is used to get default profile media.
    * @param Request  
    * @return \Illuminate\Http\JsonResponse
    */
    
    public function getDefaultProfileData(Request $request){
        try {
            
            $getDefaultProfileData = DefaultProfileRepository::getDefaultProfileData($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getDefaultProfileData,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
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
    * Function used to get user profile list.
    * @param Request  
    * @return \Illuminate\Http\JsonResponse
    */
    
    public function getGuestProfile(Request $request){
        try {

            $getProfileList = ProfileRepository::getGuestProfile($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getProfileList,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'data' => "",
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    } 
}
