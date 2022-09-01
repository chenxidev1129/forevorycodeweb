<?php

namespace App\Repositories;

use App\Models\ProfileStoriesArticle;
use Illuminate\Support\Facades\Config;
use Exception;
class ProfileStoriesArticleRepository{

    /**
     * Find one
     * @param array $where
     * @return  ProfileStoriesArticle
     */

    public static function findOne($where)
    {
        return ProfileStoriesArticle::where($where)->first();
    }

    /**
     * Find one order by
     * @param array $where
     * @return  ProfileStoriesArticle
     */

    public static function findOneOrderBy($where, $orderBy)
    {
        return ProfileStoriesArticle::where($where)->orderBy('id', $orderBy)->first();
    }

    /**
     * Function used to upload profile article image. 
     * @param $request
     * @return int id
     * @throws Exception
     */ 

    public static function uploadArticleImages($request){
        try{
            $post = $request->all();
            if($request->hasFile('images')) {
              
                $image = $request->file('images');
                $articleMediaPath = config('constants.profile_media').'/'.$post['profile_id'].'/'.getRandomName().'.'.'png';
                
                /* Upload media file into storage */
                $uploadMediaResponse = uploadMedia($articleMediaPath, $image);// uploadBaseCodeMedia($articleMediaPath, $image);
                
                if($uploadMediaResponse){
                   
                    $addStoriesArticleData['profile_id'] =  $post['profile_id'];
                    $addStoriesArticleData['image'] = $articleMediaPath;
                    
                    /* Check type is add or edit */
                    if($post['type'] == 'add'){
                        
                        $position = 1;
                        $getPosition = self::findOneOrderBy(['profile_id'=> $post['profile_id']], 'desc');
                        
                        if(!empty($getPosition)){
                            /* Increment position by one */
                            $position = $getPosition->position+1;
                        }
                        
                        $addStoriesArticleData['position'] = $position;
                        /* Create stories and article details */
                        $profileData = ProfileStoriesArticle::create($addStoriesArticleData);
                        /* Return article id */
                        return $profileData['id'];
                    
                    }else{
                        /* Get stories and article */
                        $articleData = self::findOne(['id' => $post['id']]);
                        if(!empty($articleData)){
                            /* Remove old stories and article from storage */
                            deleteUploadMedia($articleData->image);
                            ProfileStoriesArticle::where('id', $post['id'])->update($addStoriesArticleData);
                        }

                        return $post['id'];
                    } 

                }

            }
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to remove upload article image and data. 
     * @param $request
     * @return boolean
     * @throws Exception
     */ 

    public static function removeArticle($request){
        try{
            $post = $request->all();
            $getstoriesArticleData = self::findOne(['id' => $post['id']]);
            
            if(!empty($getstoriesArticleData)){

                $deleteStatus = deleteUploadMedia($getstoriesArticleData->image);
                if($deleteStatus){

                    $position = $getstoriesArticleData->position;
                    $storiesArticleDeleteResponse = ProfileStoriesArticle::where('id', $getstoriesArticleData->id)->delete();
                    
                    if(empty($storiesArticleDeleteResponse)){
                        throw new Exception('Something went wrong while deleting profile stories & article');
                    }

                    /* Update stories & articles position */
                    $updatePositionResponse = ProfileStoriesArticle::where(['profile_id'=>$getstoriesArticleData->profile_id])->where('position', '>', $position)->decrement('position');
                   
                    return true;
                }
                
                throw new Exception(__('message.something_went_wrong_delete_stories_article'));
            }
            
            throw new Exception(__('message.stories_article_not_found_to_delete'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to update article image position and title with text. 
     * @param $request
     * @return boolean
     * @throws Exception
     */ 

    public static function updateProfileArticle($request){
        try{
            $post = $request->all();
            $update = array();
            $i = 1;
            
            if(!empty($post['articles-image-position'])){
                
                foreach($post['articles-image-position'] as $key=>$value){
   
                    $update['title'] = $post['storiesArticleTitle'][$key];
                    $update['text'] = $post['storiesArticleText'][$key];
                    $update['position'] = $i;
                    $update['is_save'] = '1';
                    /* Update stories and article detail */
                    ProfileStoriesArticle::where(['profile_id' => $post['profile_id'] , 'id' => $post['articles-image-position'][$key]])->update($update);
                    $i++;

                }
            }
            return true;
        }catch(\Exception $ex){
            throw $ex;
        } 
    }  

    
    /**
     * Function used to get profile stories and article
     * @param $request
     * @return array $getProfileStoriesArticle
     * @throws Exception
     */  

    public static function getProfileStoriesArticle($request){
      
        try{
           $post = $request->all();
        
            $getProfileStoriesArticle = ProfileStoriesArticle::with('profile')->where(['profile_id' =>$post['profile_id'],'status'=> 'active' ,'is_save' => '1'])->orderBy('position', 'ASC')->paginate($post['limit']);
  
            return $getProfileStoriesArticle;

        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to add or update profile stories and article. 
     * @param $request
     * @return boolean
     * @throws Exception
     */   

    public static function profileStoriesArticles($request, $type){
        try{ 
            $post = $request->all();
            if($request->hasFile('image')) {
                
                $profileStoriesArticlesImage = $request->file('image');
                $profileMediaPath = config('constants.profile_media').'/'.$post['profile_id'].'/'.getRandomName().'.'.'png';
                
                /* Uplaod stories and article image into storage */
                $uploadMediaResponse =  uploadMedia($profileMediaPath, $profileStoriesArticlesImage);
                if($uploadMediaResponse){

                    if($type == 'add'){
                        return self::addStoriesArticleDetail($request, $profileMediaPath);
                    }else{
                        return self::updateStoriesArticleDetail($request, $profileMediaPath);
                    }  

                }
            }

            throw new Exception(__('message.media_file_not_file'));
        }catch (\Exception $ex){
            throw $ex;
        }   
    }

    /**
     * Function used to add profile stories and article. 
     * @param $request
     * @return boolean
     * @throws Exception
     */  

    public static function addStoriesArticleDetail($request, $profileMediaPath){
        
        $post = $request->all();
        $position = 1;
        
        $getPosition = self::findOneOrderBy(['profile_id'=> $post['profile_id']], 'desc'); 
        
        if(!empty($getPosition)){
            $position = $getPosition->position+1;
        }
        
        $position = !empty($post['position']) ? $post['position'] : $position;
        /* Create profile Stories & Article detail */
        $uploadProfileMediaResponse = ProfileStoriesArticle::create(['profile_id' => $post['profile_id'], 'image' => $profileMediaPath, 'title' => $post['title'], 'text' => $post['text'], 'is_save' => '1', 'position' => $position ]);
        
        if(!empty($uploadProfileMediaResponse)){
            return true;
        }

        throw new Exception(__('message.something_went_wrong_add_stories_article'));
    } 
    
    /**
     * Function used to update profile stories and article. 
     * @param $request
     * @return boolean
     * @throws Exception
     */ 

    public static function updateStoriesArticleDetail($request, $profileMediaPath){
        
        $post = $request->all();
        /* Get stories and article */
        $getStoriesArticleData = self::findOne(['id' => $post['id']]);
        
        if(!empty($getStoriesArticleData)){
            /* Remove old stories and article from storage */
            $deleteUploadMediaResponse = deleteUploadMedia($getStoriesArticleData->image);
            
            if(empty($deleteUploadMediaResponse)){
                throw new Exception(__('message.something_went_wrong_delete_stories_article_old_media'));
            }
            
            /* Update stories article detail */
            $storiesArticleUpdateResponse = ProfileStoriesArticle::where('id', $post['id'])->update(['image' => $profileMediaPath, 'is_save' => '1']);

            if(!empty($storiesArticleUpdateResponse)){
                return true;
            }

            throw new Exception(__('message.something_went_wrong_update_stories_article'));
        }
        throw new Exception(__('message.stories_article_not_found_to_update'));
    }

    /**
     * Function used to update stories and article position with caption. 
     * @param $request
     * @return boolean
     * @throws Exception
     */ 

    public static function updateStoriesArticlesPosition($request){
        try{
            $post = $request->all();
            
            if(!empty($post['stories_articles_array'])){
                $getData = $post['stories_articles_array'];  
                
                for($i = 0; $i < count($getData); $i++) {
                    
                    if(!empty($getData[$i]['id'])){       
                        $mediaPositionResponse =  ProfileStoriesArticle::where(['id' => $getData[$i]['id'] ,'profile_id' => $request['profileId']])->update(['title'=> $getData[$i]['title'], 'text' => $getData[$i]['text'], 'position' => $getData[$i]['position'] ]);
                        
                        if(empty($mediaPositionResponse)){
                            throw new Exception(__('message.something_went_wrong_while_update_stories_articles_detail'));
                        }
                       
                    }

                }
                return true;

            }
            throw new Exception(__('message.sotires_articles_detail_not_found'));
        }catch (\Exception $ex){
            throw $ex;
        } 
    } 
    
    /**
     * Function is used to get profile stories and article
     * @param $request
     * @return array $getProfileStoriesArticle
     * @throws Exception
     */ 

    public static function getProfileStoriesArticleList($request){
      
        try{
            $post = $request->all();
            $paginationLimit = Config::get('constants.DefaultValues.MEDIA_PAGINATION_RECORD');

            $getProfileStoriesArticleList = ProfileStoriesArticle::with('profile:id,user_id','profile.user:id,first_name,last_name')
            ->where(['profile_id' =>$post['profile_id'],'status'=> 'active' ,'is_save' => '1'])
            ->orderBy('position', 'ASC');
  
            /* Check page and render result */ 
            if (!empty($post['page']) && $post['page'] > 0) {
                $getProfileStoriesArticleList = $getProfileStoriesArticleList->simplePaginate($paginationLimit);
            } else {
                $getProfileStoriesArticleList = $getProfileStoriesArticleList->get();
            }

            return $getProfileStoriesArticleList;

        } catch (\Exception $ex) {
            throw $ex;
        }   
    }
}
