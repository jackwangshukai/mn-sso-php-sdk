<?php

namespace sso\Dispatch;

use Hanson\Foundation\Foundation;

/**
 * Class Dispatch
 * @method array getToken($params) 获取token
 * @method array loginOut($params) 登出
 * @method array userInfo($params)获取用户信息
 * @method array userInfoExtend($params)获取用户扩展信息
 * @method array getRoles($params)获取角色信息
 * @method array getRoutes($params)电子卡领取接口
 * @method array getPerms($params)获取用户权限列表
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