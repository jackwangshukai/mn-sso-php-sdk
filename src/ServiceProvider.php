<?php

namespace sso\Dispatch;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        // token相关接口
        $pimple['token'] = function ($pimple) {
            $config = $pimple->getConfig();
            return new Token($config);
        };
    }
}