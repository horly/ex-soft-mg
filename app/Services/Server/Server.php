<?php

namespace App\Services\Server;

class Server
{
    protected $server;

    function __construct()
    {
        $this->server = config('app.server');
    }

    public function getPublicFolder()
    {
        if($this->server == 'local')
        {
            return public_path();
        }
        else if($this->server == 'lws')
        {
            return "/home/htdocs/exaderp.exadgroup.org/ex-soft-mg/public";
        }
        else //hostinger
        {
            return  base_path() . '/public';
        }
    }
}
