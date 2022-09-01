<?php

namespace App\Repositories;

use App\Models\ProfileGraveSite;
use Exception;


class ProfileGraveSiteRepository{

    /**
     * Find one
     * @param array $where
     * @return  ProfileGraveSite
     */
    public static function findOne($where)
    {
        return ProfileGraveSite::where($where)->first();
    }

    /**
     * Function used to create profile grave site. 
     * @param $request
     * @return boolean
     * @throws Exception
     */   
    public static function addGraveSiteDetails($request){
        try{
           $post = $request->all();
           $addGraveSite = array();

             /* Check if image is in request */
             if(!empty($post['grave_image'])) {
                $image = $post['grave_image'];

                list($type, $image) = explode(';', $image);
                list(, $image) = explode(',', $image);
                $image = base64_decode($image);
                $image_name = config('constants.profile_media').'/'.$post['profile_id'].'/grave-image'.'.'.'png';

                $uploadStatus =  uploadBaseCodeMedia($image_name, $image);
                if($uploadStatus){
                    $addGraveSite['image'] = $image_name;
                }
            }

            /*If address is not empty than insert all value  */
            if(!empty($post['address'])){

                $addGraveSite['address'] = $post['address'];
                
                if(!empty($post['country'])){
                    $addGraveSite['country'] = $post['country'];
                }
                if(!empty($post['state'])){
                    $addGraveSite['state'] = $post['state'];
                }
                if(!empty($post['city'])){
                    $addGraveSite['city'] = $post['city'];
                }
                if(!empty($post['zip_code'])){
                    $addGraveSite['zip_code'] = $post['zip_code'];
                }
                
                if(!empty($post['lat'])){
                    $addGraveSite['lat'] = $post['lat'];
                }
                if(!empty($post['lang'])){
                    $addGraveSite['lang'] = $post['lang'];
                }

                $addGraveSite['note'] = !empty($post['note']) ? $post['note'] : null;

                $profileId = !empty($post['profile_id']) ? $post['profile_id'] : $post['profileId'];

                $updateOrCreateStatus = ProfileGraveSite::updateOrCreate(['profile_id' => $profileId], $addGraveSite);
                if(empty($updateOrCreateStatus)){
                    throw new Exception(__('message.something_went_wrong'));
                }
            }
            return true;

        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to remove grave site photo. 
     * @param $request
     * @return boolean
     * @throws Exception
     */   
    public static function removeGraveSitePhoto($request){
        try{
            $post = $request->all();
            $getGraveDetail = self::findOne(['id' => $post['id']]);

            if(empty($getGraveDetail)){
                throw new Exception(__('message.grave_site_detail_not_found'));
            }
               
            /* Delete grave site image from the storage */
            $graveImageDeleteResponse = deleteUploadMedia($getGraveDetail->image); 
            if(empty($graveImageDeleteResponse)){
                throw new Exception(__('message.wrong_while_deleting_grave_site_image')); 
            }

            $updateStatus = ProfileGraveSite::where(['id'=>$post['id']])->update(['image'=>null]);
            if(!empty($updateStatus)){
                return true;
            }
            return false;
        
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to get gravesite detail. 
     * @param $request
     * @return $getGraveSiteDetail
     * @throws Exception
     */   
    public static function getGraveSiteDetail($request){
        try{
           $post = $request->all();
           $getGraveSiteDetail = self::findOne(['profile_id' => $post['profile_id']]);
           return $getGraveSiteDetail;

        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to upload and update grave site image. 
     * @param $request
     * @return string
     * @throws Exception
     */       
    public static function addUpdateGraveSiteImage($request){
        try{
            $post = $request->all();
            if($request->hasFile('image')) {
                    
                $profileGraveImage = $request->file('image');
                $profileGraveMediaPath = config('constants.profile_media').'/'.$post['profile_id'].'/grave-image'.'.'.'png';
                
                /* Upload grave site image into storage */
                $uploadGraveMediaResponse =  uploadMedia($profileGraveMediaPath, $profileGraveImage);
                if(empty($uploadGraveMediaResponse)){
                    throw new Exception(__('message.something_went_wrong_while_uploading_grave_media'));
                }
    
                $profileGraveSiteResponse = ProfileGraveSite::updateOrCreate(['profile_id' => $post['profile_id']], ['image' => $profileGraveMediaPath]);   
                if(empty($profileGraveSiteResponse)){
                    throw new Exception(__('message.something_went_wrong'));
                }
    
                return true;
            }
            throw new Exception(__('message.grave_site_image_not_found'));
        }catch(\Exception $ex){ 
            throw $ex;
        }
    }
}
