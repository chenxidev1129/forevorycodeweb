<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserCardRepository;
use App\Http\Requests\Api\AddCardDetailRequest;
use App\Http\Requests\Api\DeleteCardRequest;
use App\Http\Requests\Api\DefaultCardRequest;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class PaymentMethodController extends Controller
{
       
    /**
     * Function used to add user card 
     * @param AddCardDetailRequest
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function addCard(AddCardDetailRequest $request){
        try {
            UserCardRepository::addNewCard($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.card_added_successfully'),
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
     * Function used to add user card 
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function getCard(Request $request){
        try {
            $userCardList = UserCardRepository::getSaveCard($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $userCardList, 
                    'message' => __('message.success'),
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
     * Function used to delete card.
     * @param DeleteCardRequest
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function deleteCard(DeleteCardRequest $request){
        try {
            UserCardRepository::deleteCard($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.card_delete_success'),
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
     * Function is used to make card as default.
     * @param DefaultCardRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function makeCardDefault(DefaultCardRequest $request){
        try {
            UserCardRepository::makeCardDefault($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.set_card_default_success'),
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
}
