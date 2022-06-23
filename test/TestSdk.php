<?php

namespace sso\Dispatch\test;

use PHPUnit\Framework\TestCase;
use sso\Dispatch\Sso;

class TestSdk extends Testcase
{

    /**
     * 创建订单
     *
     */
    public function testSdk()
    {
        $config = ['appKey' => '', 'appSecret' => '', 'gatewayUrl' => "", "jumpUrl" => ''];
        $sso = new Sso($config);
        $token = $sso->token;//token
        $data = [
            'grant_type' => 'authorization_code',
            'code' => 'ada4601b-5f7d-4efa-a110-ebef3ecbf8b6',
        ];
        $res = $token->getToken($data);//token操作

        $this->assertNotEmpty($res);
    }

    public function testJumpUrl()
    {
        $config = ['appKey' => '', 'appSecret' => '', 'gatewayUrl' => "", "jumpUrl" => ''];
        $sso = new Sso($config);
        $token = $sso->token;//用户相关
        $res = $token->getRedirectUrl();//token操作
        var_dump($res);
        $this->assertNotEmpty($res);
    }

    public function testUserInfo()
    {
        $config = ['appKey' => '', 'appSecret' => '', 'gatewayUrl' => "", "jumpUrl" => ''];
        $sso = new Sso($config);
        $token = $sso->token;//token操作
        $data = [
            'grant_type' => 'authorization_code',
            'code' => '7057038c-18c2-44b4-9521-24057c8ed0d2',
        ];
        $res = $token->userInfo();//查询用户信息

        $this->assertNotEmpty($res);
    }

    public function testUserInfoEtend()
    {
        $config = ['appKey' => '', 'appSecret' => '', 'gatewayUrl' => "", "jumpUrl" => ''];
        $sso = new Sso($config);
        $token = $sso->token;//token操作
        $res = $token->userInfoExtend();//查询用户信息
        $this->assertNotEmpty($res);
    }

    public function testRoles()
    {
        $config = ['appKey' => '', 'appSecret' => '', 'gatewayUrl' => "", "jumpUrl" => ''];
        $sso = new Sso($config);
        $token = $sso->token;//token操作
        $res = $token->getRoles();//查询用户信息
        $this->assertNotEmpty($res);
    }

    public function testRoutes()
    {
        $config = ['appKey' => '', 'appSecret' => '', 'gatewayUrl' => "", "jumpUrl" => ''];
        $sso = new Sso($config);
        $token = $sso->token;//token操作
        $res = $token->getRoutes();//查询用户信息
        $this->assertNotEmpty($res);
    }

    public function testPerms()
    {
        $config = ['appKey' => '', 'appSecret' => '', 'gatewayUrl' => "", "jumpUrl" => ''];
        $sso = new Sso($config);
        $token = $sso->token;//token操作
        $res = $token->getPerms();//查询用户信息

        $this->assertNotEmpty($res);
    }

    public function testLoginOut()
    {
        $config = ['appKey' => '', 'appSecret' => '', 'gatewayUrl' => "", "jumpUrl" => ''];
        $sso = new Sso($config);
        $token = $sso->token;//token操作
        $res = $token->loginOut();//查询用户信息
        $this->assertNotEmpty($res);
    }
}