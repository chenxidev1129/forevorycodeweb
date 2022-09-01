<?php

namespace App\Repositories;

use App\Http\Requests\ResetPasswordRequest;
use Symfony\Component\HttpFoundation\Request;
use App\Repositories\UserRepository;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

class PasswordResetRepository
{

    public static function findOne($where, $with = [])
    {
        return PasswordReset::with($with)->where($where)->latest('created_at')->first();
    }

    public static function delete($where)
    {
        return PasswordReset::where($where)->delete();
    }

    /**
     * Verify token
     * @param Request $request
     * @return User $user
     * @throws Throwable $th
     */
    public static function isVerifyTokenValid(Request $request)
    {
        try {
            $token = self::findOne([
                'token' => $request->verify_token
            ]);
          
            if (empty($token))
                throw new Exception(__('message.invalid_verification_token'), 1);

            $createdTimestamp = strtotime($token->created_at);
            $currentTimestamp = time();
            $expiredTimestamp =  $createdTimestamp + (1 * 60 * 60);

            if ($currentTimestamp > $expiredTimestamp)
                throw new Exception(__('message.password_reset_link_expire'), 1);

            $user = UserRepository::findOne(['email' => $token->email]);
           
            if (empty($user))
                throw new Exception(__('message.user_not_found'), 1);

            //$verifyToken = str_random(30);
             $verifyToken = getRandomId();
            
             $token->token = $verifyToken;
         
             $user->verify_token = $verifyToken;

             if (!$user->save())
                 throw new Exception(__('message.verification_error'), 1);

            return $user;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Reset password
     * @param ResetPasswordRequest $request
     * @return User $user
     * @throws Throwable $th
     */
    public static function resetPassword(ResetPasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $verifyToken = $request->verify_token;
            $user = UserRepository::findOne(['verify_token' => $verifyToken]);
            if (empty($user))
                throw new Exception(__('message.invalid_verification_token'), 1);

            $user->verify_token = '';
            $user->password = Hash::make($request->password);
            if (!$user->save())
                throw new Exception(__('message.password_reset_error'), 1);

            self::delete(['email' => $user->email]);

            DB::commit();

            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
