<?php

namespace App\Repositories;
use App\Models\AdminSetting;
use Exception;

class AdminSettingRepository{

    /**
     * Find setting by key
     * @param $request
     * @return mixed
     * @throw Exception $ex
     */
    public static function findSettingByKey($key)
    {
        try {

            $setting = AdminSetting::where('key', $key)->where('status', 'active')->first();
            return $setting;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
