<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Repositories\AdminSettingRepository;

class CheckForceUpdate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $deviceType = $request->header('device-type');
        $appVersion = $request->header('app-version');
        $appVersionKey = '';
        $allowAppUpdateKey = '';

        /* Check user device */
        if ($deviceType == 'ios') {
            $appVersionKey = 'customer_ios_version';
            $allowAppUpdateKey = 'customer_update_ios';
        } else{
            $appVersionKey = 'customer_android_version';
            $allowAppUpdateKey = 'customer_update_android';
        }

        /* Check app version */
        if (!empty($appVersionKey)) {

            $appVersion = (float) $appVersion;
            $allowAppUpdateDetail = AdminSettingRepository::findSettingByKey($allowAppUpdateKey);
            
            if (!empty($allowAppUpdateDetail) && $allowAppUpdateDetail->value == 1) {

                $appVersionDetail = AdminSettingRepository::findSettingByKey($appVersionKey);
               
                if (!empty($appVersionDetail)) {

                    $appNewVersion = (float) $appVersionDetail->value;
                   
                    if ($appVersion < $appNewVersion) {
                        return response()->json(
                            [
                                'success' => false,
                                'data' => [],
                                'message' => __('message.version_update')
                            ],
                            Config::get('constants.HttpStatus.FORBIDDEN')
                        );
                    }
                }
            }
        }
        return $next($request);
    }
}
