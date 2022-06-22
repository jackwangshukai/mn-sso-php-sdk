<?php

namespace sso\Dispatch;

use Hanson\Foundation\Foundation;

/**
 * Class Dispatch
 */
class Dispatch extends Foundation
{
    private $service;

    /**
     * @param $config
     */
    public function __construct(string $service, $config)
    {
        parent::__construct($config);
        $this->service = new $service($config);
    }

    public function __call($name, $arguments)
    {
        return $this->service->{$name}(...$arguments);
    }

}