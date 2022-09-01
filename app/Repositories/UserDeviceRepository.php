<?php

namespace App\Repositories;
use Illuminate\Http\Request;
use App\Models\UserDeviceToken;

class UserDeviceRepository{

    /**
     * find row.
     * @param array $where
     * @return  UserDeviceToken
     */
    public static function findOne($where)
    {
        return UserDeviceToken::where($where)->first();
    }    
  
    /**
     * Delete device token.
     * @param array $where
     * @return  UserDeviceToken
     */
    public static function deleteWhere($where)
    {
        return UserDeviceToken::where($where)->delete();
    }

    /**
     * Create device token.
     * @param array $data
     * @return  UserDeviceToken
     */
    public static function insertDeviceData($data)
    {
        return UserDeviceToken::create($data);
    }

    /**
     * Create or update device token.
     * @param array $data
     * @return updateOrCreate
     */
    public static function updateOrCreate($where= array(), $data)
    {
        return UserDeviceToken::updateOrCreate($where, $data);
    }
}
