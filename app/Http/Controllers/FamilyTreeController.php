<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProfileRepository;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class FamilyTreeController extends Controller
{

    /**
     * Function to load family tree.
     * @return \Illuminate\Http\Response 
     */
    public function index(Request $request,$profile_id){

        $profile = ProfileRepository::findOne(['id' => $profile_id]);
        if(!empty($profile)) {
            return view('user.profile.family-tree',compact('profile'));
        } else {
            return redirect('profile');
        }
    }

    /**
     * Function to show family tree.
     * @return \Illuminate\Http\Response 
     */
    public function getFamilyTree(Request $request){
        try {
            $profile = ProfileRepository::findOne(['id' => $request->profileId]);
            return  response()->json(
                [
                    'success' => true,
                    'data' => $profile->family_tree,
                    'message' => ''
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
     * Function to save family tree.
     * @return \Illuminate\Http\Response 
     */
    public function saveFamilyTree(Request $request){
        try {
            $profile = ProfileRepository::saveFamilyTree($request);
            return  response()->json(
                [
                    'success' => true,
                    'data' => $profile,
                    'message' => ''
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
     * Function to upload family member images.
     * @return \Illuminate\Http\Response 
     */
    public function uploadMemberImage(Request $request){
        try {
            $memberImage = ProfileRepository::uploadProfileMemberImage($request);
            return  response()->json(
                [
                    'success' => true,
                    'data' => $memberImage,
                    'message' => ''
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

    
}
