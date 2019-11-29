<?php

namespace ai\face\recognition\providers;

use GuzzleHttp\Client;
use think\facade\Cache;
use think\Request;

abstract class AbstractProvider
{
    protected $request;

    protected $config;

    protected $options = [];

    public function __construct(Request $request, $config)
    {
        $this->request = $request;
        $this->config = $config;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        return new Client();
    }

    protected function cache()
    {
        return Cache::init();
    }
}