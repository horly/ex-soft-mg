<?php

namespace App\Services\Device;

use Jenssegers\Agent\Facades\Agent;

/**
 * installation 
 * - composer require jenssegers/agent
 */

 class Device
 {
    public function getDevice()
    {
        return Agent::device();
    }

    public function getBrowser()
    {
        return Agent::browser();
    }

    public function getPlatform()
    {
        return Agent::platform();
    }
 }