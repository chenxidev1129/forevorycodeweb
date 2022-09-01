<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProfileGraveSiteRepository;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class GraveSiteController extends Controller
{
    
    /**
     * Function used to load gravesite detail page
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function loadGravesiteDetail(Request $request){
        try {
            $getGraveSiteDetail = ProfileGraveSiteRepository::getGraveSiteDetail($request);
            $html = View::make('admin.accounts.profile.gravesite-details', compact('getGraveSiteDetail'))->render();
            return response()->json(
                [
                    'success' => true,
                    'html' => $html
                ],
                Response::HTTP_OK
            );
        } catch (   Exception $ex) {
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
     * Function used to show viewAllPrayers
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function loadViewAllPrayers(Request $request){
        try {
          
            $html = View::make('admin.accounts.profile.view-all-prayers')->render();
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
}
