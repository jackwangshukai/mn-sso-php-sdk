<?php

namespace sso\Dispatch;


use Hanson\Foundation\Foundation;
/**
 * @property Token $token
 *
 * Class Sso
 */
class Sso extends Foundation
{
    protected $providers = [
        ServiceProvider::class
    ];
    public function __construct($config)
    {
        $config['debug'] = $config['debug'] ?? false;
        parent::__construct($config);
    }

}