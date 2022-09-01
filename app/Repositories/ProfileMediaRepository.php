<?php

namespace App\Repositories;

use App\Models\ProfileMedia;
use App\Jobs\SendEmailJob;
use App\Jobs\SendPushNotificationJob;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Exception;
use FFMpeg;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Aws\Lambda\LambdaClient;
use Aws\Credentials\Credentials;

class ProfileMediaRepository{

    /**
     * Find one
     * @param array $where
     * @return  ProfileMedia
     */
    public static function findOne($where , $with = [])
    {
        return ProfileMedia::with($with)->where($where)->first();
    }

    /**
     * Find one order by
     * @param array $where
     * @return  ProfileMedia
     */
    public static function findOneOrderBy($where, $orderBy)
    {
        return ProfileMedia::where($where)->orderBy('id', $orderBy)->first();
    }

    /**
     * Function used to upload profile media image. 
     * @param $request
     * @return int id
     * @throws Exception
     */   
    public static function uploadMediaImages($request){
        try{
           
            $post = $request->all();
            if($request->hasFile('image')) {
                
                $profileMediaImage = $request->file('image');
                $profileMediaPath = config('constants.profile_media').'/'.$post['profile_id'].'/'.getRandomName().'.'.'png';
                $uploadMediaResponse =  uploadMedia($profileMediaPath, $profileMediaImage);

                if($uploadMediaResponse){
  
                    $position =  1;
                    $getPosition = self::findOneOrderBy(['profile_id'=> $post['profile_id'], 'type' => 'image'], 'desc');
                
                    if(!empty($getPosition)){

                        $position = $getPosition->position+1;
                    }
                    /* position coming from api side */
                    $position = !empty($post['position']) ? $post['position'] : $position;
                    $uploadData['type'] = 'image';
                    $uploadData['media'] = $profileMediaPath;
                    $uploadData['position'] = $position;
                    $uploadData['profile_id'] = $post['profile_id'];
                    /* Caption is optional */
                    if(!empty($post['caption'])){
                        $uploadData['caption'] = $post['caption'];
                    }
                    /* Create profile media image detail */
                    $uploadProfileMediaResponse = ProfileMedia::create($uploadData);
                    
                    if(!empty($uploadProfileMediaResponse)){
                    
                        return $uploadProfileMediaResponse['id'];
                    } 
                }
                throw new Exception(__('message.something_went_wrong_uploading_media_image'));
            }
            throw new Exception(__('message.media_file_not_file'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to upload profile media video. 
     * @param $request
     * @return array
     * @throws Exception
     */   
    public static function uploadMediaVideo($request){
        try{
            $post = $request->all();
            if($request->hasFile('video')) {
            
                $profileMedia = $request->file('video');
                $randomName = getRandomName();
                $uploadPath = config('constants.profile_media').'/'.$post['profile_id'].'/';
                $fileName = $randomName.'.'.$profileMedia->getClientOriginalExtension();
             
                $filePath = $uploadPath.$fileName;
                $profileMediaPath = $uploadPath.$randomName.'.'.'mp4';
                /* Upload file into storage */
                $uploadMediaResponse =  uploadMedia($filePath, $profileMedia);
                if($uploadMediaResponse){

                    $credentials = new Credentials(config('constants.aws_access_key_id'), config('constants.aws_secret_access_key'));
                    $client = LambdaClient::factory(array(
                        'credentials' => $credentials,
                        'region' => 'us-east-2',
                        'version' => 'latest'
                    ));
                    
                    $result = $client->invoke([
                        // Trigger lambda funtion to create thumb and mp4 video.
                        'FunctionName' => 'Thumbnail',
                        'Payload'=> json_encode([
                            "bucket"=>"forevory-public-bucket",
                            "key"=> $filePath,
                            "id"=> getRandomName()
                        ])
                    ]);
                    
                    //Log::debug('lambda', ['lambda' =>$result->get('Payload')]);
                 
                    /* Convert video file formate to mp4 */
                    if($profileMedia->getClientOriginalExtension() !== 'mp4'){
                        deleteUploadMedia($filePath);
                    }

                    $ffprobe = \FFMpeg\FFProbe::create([	
                        'ffmpeg.binaries'  => config('constants.ffmpeg'),	
                        'ffprobe.binaries' => config('constants.ffprobe')	
                    ]);

                    $videoDuration = $ffprobe->format(Storage::url($profileMediaPath))->get('duration');

                    $f = ':';
                    if(!empty($videoDuration) &&  $videoDuration >= 3600){
                        $videoDuration =  sprintf("%02d%s%02d%s%02d", floor($videoDuration/3600), $f, ($videoDuration/60)%60, $f, $videoDuration%60);
                    }else{
                        $videoDuration =  sprintf("%02d%s%02d", floor($videoDuration/60)%60, $f, $videoDuration%60);  
                    }

                    $thumbName = $randomName.'.png';
                
                    /* Default position */
                    $position =  1;
                    /* Find last position of the profile media */
                    $getPosition = self::findOneOrderBy(['profile_id'=> $post['profile_id'], 'type' => 'video'], 'desc');
                    if(!empty($getPosition)){
                        /* Increment position by +1 */
                        $position = $getPosition->position+1;
                    }
                    
                    /* $post['position'] coming from api side */
                    $position = !empty($post['position']) ? $post['position'] : $position;
                    
                    $updateData['profile_id'] = $post['profile_id'];
                    $updateData['type'] = 'video';
                    $updateData['media'] = $profileMediaPath;
                    $updateData['position'] = $position;
                    $updateData['duration'] = !empty($post['duration']) ? $post['duration'] : $videoDuration;
                    $updateData['thumbnail'] =  $uploadPath.$thumbName;
                    
                    /* Video caption optional */
                    if(!empty($post['caption'])){
                    
                        $updateData['caption'] = $post['caption'];
                    }
                    /* Create profile media detail */
                    $profileData = ProfileMedia::create($updateData);
                    
                    if(!empty($profileData)){
                        
                        /* Return profile id and media url */
                        return array('id' => $profileData['id'] , 'media' => $profileData['media'], 'duration' => $videoDuration, 'media_with_url' => $profileData['media_with_url']);
                    }
                }
                throw new Exception(__('message.something_went_wrong_uploading_media_video'));
            }
            throw new Exception(__('message.media_file_not_file'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to update profile media video. 
     * @param $request
     * @return string
     * @throws Exception
     */   
    public static function updateMediaVideo($request){
        try{
            $post = $request->all();
            /* Get uploaded video */
            $getUploadedVideo = self::findOne(['id'=>$post['id']]);
             /* Check if file exist */
            if($request->hasFile('video') && !empty($getUploadedVideo)) {

                $profileMedia = $request->file('video');
                $randomName = getRandomName();
                $uploadPath = config('constants.profile_media').'/'.$post['profile_id'].'/';
                $fileName = $randomName.'.'.$profileMedia->getClientOriginalExtension();
               
                $filePath = $uploadPath.$fileName;
                $profileMediaPath = $uploadPath.$randomName.'.'.'mp4';
                /* Upload file into storage */
                $uploadMediaResponse =  uploadMedia($filePath, $profileMedia);

                if($uploadMediaResponse){
                     
                    
                    $credentials = new Credentials(config('constants.aws_access_key_id'), config('constants.aws_secret_access_key'));
                    $client = LambdaClient::factory(array(
                        'credentials' => $credentials,
                        'region' => 'us-east-2',
                        'version' => 'latest'
                    ));
                    
                    $result = $client->invoke([
                        // Trigger lambda funtion to create thumb and mp4 video.
                        'FunctionName' => 'Thumbnail',
                        'Payload'=> json_encode([
                            "bucket"=>"forevory-public-bucket",
                            "key"=> $filePath,
                            "id"=> getRandomName()
                        ])
                    ]); 

                    //Log::debug('lambda', ['lambda' =>$result->get('Payload')]);
                    /* Convert video file formate to mp4 */
                
                    if($profileMedia->getClientOriginalExtension() !== 'mp4'){
                        deleteUploadMedia($filePath);
                    }

                    $ffprobe = \FFMpeg\FFProbe::create([	
                        'ffmpeg.binaries'  => config('constants.ffmpeg'),	
                        'ffprobe.binaries' => config('constants.ffprobe')	
                    ]);
                    $videoDuration =  $ffprobe->format(Storage::url($profileMediaPath))->get('duration');

                    $f = ':';
                    if(!empty($videoDuration) &&  $videoDuration >= 3600){
                        $videoDuration =  sprintf("%02d%s%02d%s%02d", floor($videoDuration/3600), $f, ($videoDuration/60)%60, $f, $videoDuration%60);
                    }else{
                        $videoDuration =  sprintf("%02d%s%02d", floor($videoDuration/60)%60, $f, $videoDuration%60);  
                    }

                    /* Create vedio thumbnail  */
                    $thumbName = $randomName.'.png';
                  
                    $updateMediaResponse =  ProfileMedia::where(['id'=>$post['id'],'type'=>'video'])->update(['media'=>$profileMediaPath,'duration'=>!empty($post['duration']) ? $post['duration'] : $videoDuration, 'thumbnail'=> $uploadPath.$thumbName]);

                    if(empty($updateMediaResponse)){
                        throw new Exception(__('message.something_went_wrong_updating_media_video'));
                    }

                    /* Remove updated video from storage */
                    $deleteOldMediaVideo = deleteUploadMedia($getUploadedVideo->media);
                    if(empty($deleteOldMediaVideo)){
                        throw new Exception(__('message.something_went_wrong_delete_old_media_video'));
                    }

                    /* Remove video thumb image */
                    $deleteVideoThumb = deleteUploadMedia($getUploadedVideo->thumbnail);
                    if(empty($deleteVideoThumb)){
                        throw new Exception(__('message.something_went_wrong_delete_old_media_video'));
                    }
                    
                    return array('mediaUrl' => $profileMediaPath, 'duration' => $videoDuration, 'media_with_url'=> getUploadMedia($profileMediaPath)); 

                }
            }
            throw new Exception(__('message.media_file_not_file'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    } 

    /**
     * Function used to remove profile media image video and audio.
     * @param $request
     * @return boolean
     * @throws Exception
     */   
    public static function removeUploadMedia($request){
        try{
            $post = $request->all();
            $profileData = self::findOne(['id' => $post['id']]);
            
            if(!empty($profileData)){
                /* Delete media file from Storage */ 
                $deleteStatus = deleteUploadMedia($profileData->media);
                if($deleteStatus){

                    $position = $profileData->position;
                    if($profileData->type == 'video' && !empty($profileData->thumbnail)){
                        /* Delete video thumbnail from the storage */
                        deleteUploadMedia($profileData->thumbnail);    
                    }
                    /* Delete detail from db */
                    ProfileMedia::where('id', $profileData->id)->delete();
                    if(in_array($profileData->type, ['image', 'video'])){
                       
                       /*Update position greater than deleted positon */
                       ProfileMedia::where(['profile_id'=>$post['profile_id']])->where('position', '>', $position)->decrement('position');
                    
                    }
                    return true;
                }
                throw new Exception(__('message.something_went_wrong_delete_profile_media'));
            }
           throw new Exception(__('message.media_file_not_file'));

        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to update profile image caption and position. 
     * @param $request
     * @throws Exception
     * @return boolean
     */
    public static function updateProfileImageCaption($request){
        try{
            $post = $request->all();
            $update = array();
        
            if(!empty($post['image_position'])){
                for ($i = 0; $i < count($post['image_position']); $i++) {
                    if(!empty($post['image_position'])){
                       
                        /* Get caption index */
                        $captionId = $post['image_position'][$i];
                        $update['caption'] = $post['image_caption'][$captionId];
                        $update['position'] = $i+1;
                        
                        ProfileMedia::where(['profile_id' => $post['profile_id'] , 'id' => $post['image_position'][$i]])->update($update);
                    
                    }
                }
            }
            return true;
          } catch (\Exception $ex) {
              throw $ex;
          } 
    }

    /**
     * Function used to update profile video caption and position. 
     * @param $request
     * @throws Exception
     * @return boolean
     */
    public static function updateProfileVideoCaption($request){
        try{
            $post = $request->all();
            $update = array();
           
            if(!empty($post['video_position'])){
                for ($i = 0; $i < count($post['video_position']); $i++) {
                    if(!empty($post['video_position'])){
                       
                        /* Get caption index */
                        $captionId = $post['video_position'][$i];
                        $update['caption'] = $post['video_caption'][$captionId];
                        $update['position'] = $i+1;
                        
                        ProfileMedia::where(['profile_id' => $post['profile_id'] , 'id' => $post['video_position'][$i]])->update($update);
                    
                    }
                }
            }
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        } 
    }
    

    /**
     * Function used to upload profile voice note. 
     * @param $request
     * @return id
     * @throws Exception
     */   
    public static function uploadProfileVoiceNote($request){
        try{
            $post = $request->all();
            if($request->hasFile('audio')) {
            
                $file = $request->file('audio');
                $uploadMediafilePath = config('constants.profile_media').'/'.$post['profile_id'].'/'.getRandomName().'.'.'mp3';
                /* upload audio file into storage */
                $uploadMediaResponse =  uploadMedia($uploadMediafilePath, $file);
                
                if($uploadMediaResponse){

                    $ffprobe = \FFMpeg\FFProbe::create([	
                        'ffmpeg.binaries'  => config('constants.ffmpeg'),	
                        'ffprobe.binaries' => config('constants.ffprobe')	
                    ]);
                    $audioFileDuration =  $ffprobe->format(Storage::url($uploadMediafilePath))->get('duration');
                    $f = ':';
                    if(!empty($audioFileDuration) &&  $audioFileDuration >= 3600){
                        $audioDuration =  sprintf("%02d%s%02d%s%02d", floor($audioFileDuration/3600), $f, ($audioFileDuration/60)%60, $f, $audioFileDuration%60);
                    }else{
                        $audioDuration =  sprintf("%02d%s%02d", floor($audioFileDuration/60)%60, $f, $audioFileDuration%60);  
                    }
                    
                    /* Get login user */
                    $getUser = getUserDetail();
                    $updateData['profile_id'] = $post['profile_id'];
                    $updateData['type'] = 'audio';
                    $updateData['media'] = $uploadMediafilePath;
                    $updateData['duration'] = !empty($post['duration']) ? $post['duration'] : $audioDuration;
                    $updateData['user_id'] = $getUser->id;
                    /* Caption is optional */
                    if(!empty($post['caption'])){
                        $updateData['caption'] = $post['caption'];
                    }
                    /* Create voice note detail */
                    $voiceNoteData = ProfileMedia::create($updateData);
                    if(!empty($voiceNoteData)){
                        $getProfileMedia =  self::findOne(['id'=>$voiceNoteData['id']], ['user','profile.user:id,first_name,last_name,email']); 
                     
                        if(!empty($getProfileMedia)){
                            if($getProfileMedia->user_id != $getProfileMedia->profile->user->id){
                               
                                /* Check if user has email */
                                if(!empty($getProfileMedia->profile->user) && $getProfileMedia->profile->user->email){
                                   
                                    /* Send email to profile user about the sign guest book */ 
                                    SendEmailJob::dispatch(
                                        [
                                            'email'=> $getProfileMedia->profile->user->email
                                        ], 
                                        [
                                            'signed_user' => ucwords($getProfileMedia->user->first_name.' '.$getProfileMedia->user->last_name),
                                            'profile_user' => ucwords($getProfileMedia->profile->user->first_name.' '.$getProfileMedia->profile->user->last_name),
                                            'profile_name' => ucwords($getProfileMedia->profile->profile_name), 
                                            'subjectLine' => 'Guest book has been signed by a '.ucwords($getProfileMedia->user->first_name.' '.$getProfileMedia->user->last_name),
                                            'template' => 'user.email.profile.signed-the-guest-book'
                                        ]
                                    ); 

                                    /* Send notification to profile user about the sign guest book */ 
                                    SendPushNotificationJob::dispatch(
                                        [
                                            'user_id' => $getProfileMedia->profile->user->id,
                                            'profile_id' => $getProfileMedia->profile->id,
                                            'title' =>  "Guest book was signed",
                                            'message' => ucwords($getProfileMedia->user->first_name.' '.$getProfileMedia->user->last_name)." signed ".ucwords($getProfileMedia->profile->user->first_name.' '.$getProfileMedia->profile->user->last_name)."'s guest book",
                                            'type' => 'signGuestBook'
                                        ]
                                    );
                                }
                            }
                            return $getProfileMedia;
                        }
                    }
                    throw new Exception('Somthing went wrong. please try again later');
                }
            }
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to update voice note caption. 
     * @param $request
     * @throws Exception
     * @return boolean
     */
    public static function updateVoiceNoteCaption($request){
        try{
            $post = $request->all();
            $updateVoiceCaption = array();
            
            if(!empty($post['audio_caption_id'])){
                for ($i = 0; $i < count($post['audio_caption_id']); $i++) {
        
                    $updateVoiceCaption['caption'] = ucfirst($post['audio_caption'][$i]);
                    
                    ProfileMedia::where(['profile_id' => $post['profile_id'] ,'type' => 'audio', 'id' => $post['audio_caption_id'][$i]])->update($updateVoiceCaption);
                  
                }
            }
            return true;
          } catch (\Exception $ex) {
              throw $ex;
          } 
    }

    /**
     * Function used to update media image. 
     * @param $request
     * @return string
     * @throws Exception
     */   
    public static function updateMediaImage($request){
        try{
            $post = $request->all();
            /* Get profile image detail */
            $getImage = self::findOne(['id'=>$post['id']]);
           
            /* Check if file exist */
            if($request->hasFile('image') && !empty($getImage)) {
                
                $file = $request->file('image');
                $uploadMediaImagePath = config('constants.profile_media').'/'.$post['profile_id'].'/'.getRandomName().'.'.'png';
                
                /* Upload image into storage */
                $uploadMediaResponse =  uploadMedia($uploadMediaImagePath, $file);

                if(empty($uploadMediaResponse))
                throw new Exception(__('message.something_went_wrong_updating_media_image'));

                /* Update image */
                $updateMediaImageResponse =  ProfileMedia::where(['id'=>$post['id'],'type'=>'image'])->update(['media'=>$uploadMediaImagePath]);
                if(!empty($updateMediaImageResponse)){
                    
                    /* Remove old media image from storage */
                    $deleteResult = deleteUploadMedia($getImage->media);
                    if($deleteResult){
                        return getUploadMedia($uploadMediaImagePath);
                    } 

                    throw new Exception(__('message.something_went_wrong_delete_old_media_image'));
                }  
            }

            throw new Exception(__('message.media_file_not_file'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }
  

    /**
     * Function used to get profile media voice note. 
     * @param $request
     * @return array $getProfileMediaAudio
     * @throws Exception
     */   
    public static function getProfileMediaAudio($request){
      
        try{
            $post = $request->all();
           
            $getProfileMediaAudio = ProfileMedia::select('profile_media.id','profile_media.media','profile_media.caption','profile_media.created_at','profile_media.duration','users.first_name','users.last_name','users.image')->join('profiles', function ($join){
                $join->on('profiles.id', '=', 'profile_media.profile_id');
              
            })->join('users', function ($join){
                $join->on('users.id', '=', 'profile_media.user_id')->where(['users.status'=> 'active']);
              
            })->where(['profile_media.status' => 'active', 'profile_media.profile_id' => $post['profile_id']])->where('profile_media.type','audio')->paginate($post['limit']);
  
            return $getProfileMediaAudio;

        } catch (\Exception $ex) {
            throw $ex;
        }   
    }  
    
    /**
     * Function used to get profile guest book
     * @param $request
     * @return array $getProfileGuestBook
     * @throws Exception
     */   
    public static function getProfileGuestBook($request){
      
        try{
            $post = $request->all();

            $getProfileGuestBook = ProfileMedia::select('users.first_name','users.last_name','users.image','profile_media.created_at')
            ->join('profiles', function ($join){

                $join->on('profiles.id', '=', 'profile_media.profile_id')->where(function ($query) {
                    $query->where('profiles.status', 'active')
                          ->orWhere('profiles.status', 'expired');
                });
              
            })->join('users', function ($join) use($post){

                $join->on('users.id', '=', 'profile_media.user_id')->where(['users.status'=> 'active','users.user_type'=> 'user'])->whereNotIn('profile_media.user_id',function($query) use($post){
                $query->select('user_id')->from('profiles')->where('profiles.id', $post['profile_id']);
                });
            
            })->where(['profile_media.status' => 'active', 'profile_media.profile_id' => $post['profile_id'],'profile_media.type' => 'audio'])->orderBy('profile_media.id', 'desc')->paginate($post['limit']);

            return $getProfileGuestBook;
           
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Get login in user signed guest book
     * @param $request
     * @return array $getUserSignedBook
     * @throws Exception
     */
    public static function getUserSignedBook($request)
    {
        try{
            $getUser = getUserDetail();
            
            $getUserSignedBook = ProfileMedia::select('profiles.profile_name','profiles.date_of_birth','profiles.date_of_death','profiles.short_description','profiles.profile_image')
            
            ->join('profiles', function ($join) use($getUser){
                $join->on('profile_media.profile_id', '=', 'profiles.id')->where('profiles.status','active')->whereNotIn('profiles.user_id', [$getUser->id]);
            })
            ->join('users', 'profiles.user_id', '=', 'users.id')
            ->where(['profile_media.status'=>'active','profile_media.type' => 'audio','profile_media.user_id'=>$getUser->id])->orderBy('profile_media.id', 'desc')->get();
        
            return $getUserSignedBook;
        
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Get active visitor count
     * @return visitorCount
     */
    public static function activeVisitorCount($request='', $startDate='', $endDate='')
    {   
        if(empty($startDate) && empty($endDate)){
            $startDate = Carbon::now()->startOfYear()->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');
        }

        $profileVisitor = ProfileMedia::where('type','audio');
      
        $profileVisitor = $profileVisitor->whereHas('mediaSubscription', function($q) use($request, $startDate, $endDate){
            $q->whereBetween(DB::raw('DATE(created_at)'),[$startDate,$endDate]);
            if(!empty($request['subscriptionPlan']) && $request['subscriptionPlan'] != 'all'){
                $q->where('purchase_plan_id', $request['subscriptionPlan']);
            }
        });
      
        $profileVisitor =  $profileVisitor->where('user_id','>','0')
        ->groupBy('user_id')
        ->get();
        if(!empty($profileVisitor)) {
            return count($profileVisitor);
        } else {
            return 0;
        }
    }

    /**
     * Get active visitor
     * @return activeVisitor
     */
    public static function getActiveVisitor()
    {   
        return ProfileMedia::where('type','audio')->where('user_id','>','0')->groupBy('user_id')->get();
    }

    /**
     * Function used to update profile image caption and position. 
     * @param $request
     * @return boolean
     * @throws Exception
     */
    public static function updateMediaPosition($request){
       
        try{
            $post = $request->all();

            if(!empty($post['images_array'])){
                $getData = $post['images_array'];
                /* Get array data */
                for($i = 0; $i < count($getData); $i++) {
                    if(!empty($getData[$i]['id'])){
                       
                        $updateData['caption'] = $getData[$i]['caption'];
                        
                        if(!empty($getData[$i]['position'])){
                            $updateData['position'] = $getData[$i]['position'];
                        }

                        $mediaPositionResponse =  ProfileMedia::where(['id' => $getData[$i]['id'] ,'profile_id' => $request['profileId']])->update($updateData);
                        
                        if(empty( $mediaPositionResponse)){
                            throw new Exception(__('message.something_went_wrong_while_updating_media_detail'));
                        }
                       
                    }
                }
                return true;
            }
            throw new Exception(__('message.position_caption_not_found'));
           
          }catch(\Exception $ex) {
            throw $ex;
          } 
    }

    /**
     * Function is used to to get profile media with pagination. 
     * @param $request
     * @return array
     * @throws Exception
     */    
    public static function getProfileMedia($request){
        try{
            $post = $request->all();
            $paginationLimit = Config::get('constants.DefaultValues.MEDIA_PAGINATION_RECORD');
           
            $list = ProfileMedia::where('profile_id', $post['profile_id']);
            if(!empty($post['type']) && $post['type'] == 'image'){
                
                $list = $list->where(['type' => 'image', 'status' => 'active'])
                ->orderBy('position','asc');
            
            }elseif(!empty($post['type']) && $post['type'] == 'video'){
                
                $list = $list->where(['type' => 'video', 'status' => 'active'])
                ->orderBy('position','asc');
            
            }else{
                
                $list = $list->with('user')
                ->where(['type' => 'audio', 'status' => 'active']);
                $list->orderBy('profile_media.id','asc');
            
            }
            /* Check page and render result */ 
            if (!empty($post['page']) && $post['page'] > 0) {
                $list = $list->simplePaginate($paginationLimit);
            } else {
                $list = $list->get();
            }
            return $list;

        }catch(\Exception $ex){
            throw $ex;
        }

    }    

    /**
     * Function is used to get profile guest book list.
     * @param $request
     * @return array $getProfileGuestBook
     * @throws Exception
     */   
    public static function getProfileGuestBookList($request){
      
        try{
            $post = $request->all();
            $paginationLimit = Config::get('constants.DefaultValues.GUEST_BOOK_PAGINATION_RECORD');
            
            $getProfileGuestBook = ProfileMedia::select('users.first_name','users.last_name','users.image','profile_media.created_at')
            ->join('profiles', function ($join){
                $join->on('profiles.id', '=', 'profile_media.profile_id')->where(['profiles.status'=> 'active']);
              
            })->join('users', function ($join) use($post){
                /* Not get the list of login user  */
                $join->on('users.id', '=', 'profile_media.user_id')->where(['users.status'=> 'active','users.user_type'=> 'user'])->whereNotIn('profile_media.user_id',function($query) use($post){
                $query->select('user_id')->from('profiles')->where('profiles.id', $post['profile_id']);
                });
            
            })->where(['profile_media.status' => 'active', 'profile_media.profile_id' => $post['profile_id'],'profile_media.type' => 'audio'])->orderBy('profile_media.id', 'desc');
          
            /* Check page and render result */ 
            return $getProfileGuestBook->simplePaginate($paginationLimit);

        } catch (\Exception $ex) {
            throw $ex;
        }   
    }
    
    /**
     * Get login in user signed guest book list.
     * @param $request
     * @return array $getUserSignedBookList
     * @throws Exception
     */
    public static function getUserSignedBookList($request)
    {
        try{
            $post = $request->all();
            $getUser = getUserDetail();
            $paginationLimit = Config::get('constants.DefaultValues.GUEST_BOOK_PAGINATION_RECORD');

            $getUserSignedBookList = ProfileMedia::select('profiles.profile_name','profiles.date_of_birth','profiles.date_of_death','profiles.short_description','profiles.profile_image','profile_media.created_at')
            
            ->join('profiles', function ($join) use($getUser){
                
                $join->on('profile_media.profile_id', '=', 'profiles.id')->where('profiles.status','active')->whereNotIn('profiles.user_id', [$getUser->id]);
            
            })
            ->join('users', 'profiles.user_id', '=', 'users.id')
            ->where(['profile_media.status'=>'active','profile_media.type' => 'audio','profile_media.user_id'=>$getUser->id])->orderBy('profile_media.id', 'desc');

             /* Check page and render result */ 
            if(!empty($post['page']) && $post['page'] > 0) {
                $getUserSignedBookList = $getUserSignedBookList->simplePaginate($paginationLimit);
            }else{
                $getUserSignedBookList = $getUserSignedBookList->get();;
            }
        
            return $getUserSignedBookList;
        
        } catch (\Exception $ex) {
            throw $ex;
        }   
    } 
    
    /**
     * Get user guest book latest detail.
     * @param $request
     * @return array $getUserSignedBook
     * @throws Exception
     */
    public static function getProfileGuestLatestBook($request =array())
    {
        try{
            return ProfileMedia::with('user')->whereNotIn('user_id', [$request->user_id])->where('profile_id', $request->profileId)->first();
        } catch (\Exception $ex) {
            throw $ex;
        } 
  
    }
}
