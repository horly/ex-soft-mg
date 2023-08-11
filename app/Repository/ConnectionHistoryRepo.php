<?php

namespace App\Repository;

use App\Models\ConnectionHistory;
use App\Services\Device\Device;

class ConnectionHistoryRepo 
{
    public function getIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return server ip when no client ip found
    }

    function createHistory($user_id)
    {
        $device = new Device;
        $browser = $device->getBrowser();
        $platform = $device->getPlatform();
        $ip = $this->getIp();

        
        return ConnectionHistory::create([
            'ip' => $ip,
            'platform' => $platform,
            'browser' => $browser,
            'user_id' => $user_id
        ]);
    }

    function getHistoryByUser($user_id)
    {
        return ConnectionHistory::where('user_id', $user_id)
                    ->orderBy('id', 'desc')
                    ->take(500)
                    ->get();
    }
}