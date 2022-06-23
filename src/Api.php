<?php

namespace sso\Dispatch;

use Dq\DqDispatch\Exception\DqDispatchException;
use GuzzleHttp\Exception\RequestException;
use Hanson\Foundation\AbstractAPI;
use sso\Dispatch\Exception\SsoException;

abstract class Api extends AbstractAPI
{


    public $appKey;
    public $appSecret;
    public $gatewayUrl = "";
    public $jumpUrl = "";

    public function __construct(array $config)
    {
        $this->appKey = $config['appKey'];
        $this->appSecret = $config['appSecret'];
        $this->gatewayUrl = $config['gatewayUrl'];
        $this->jumpUrl = $config['jumpUrl'];
    }

    /**
     * @throws DqDispatchException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(string $method, array $params, string $httpMethod = 'POST',$needToken=false)
    {
       $headerArr['token-client'] = ' basic ' . $this->signature();
        if($needToken){
            $headerArr['token-auth'] = ' bearer ' . $this->getToken($params);
        }
        $jsonData = [];
        $uri = $this->gatewayUrl . $method;
        if ($httpMethod == 'POST') {
            $headerArr['Content-Type'] = 'application/json';
            $jsonData['json'] = $params;
        } else {
            $uri .= "?" . http_build_query($params);
        }
        $option = ['headers' => $headerArr];
        $option = array_merge($option, $jsonData);
        $http = $this->getHttp();
        try {
            $response = $http->getClient()->request($httpMethod, $uri, $option);
            $result = json_decode(strval($response->getBody()), true);
            $this->checkErrorAndThrow($result);
            return $result;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $result = json_decode(strval($e->getResponse()->getBody()), true);
                $this->checkErrorAndThrow($result);
                return $result;
            }

        }
    }

    public function checkErrorAndThrow($result)
    {
        if (!$result) {
            throw new SsoException('返回返回数据为空', 500);
        }
    }

    public function signature(): string
    {
        return base64_encode($this->appKey . ":" . $this->appSecret);
    }
    abstract  public  function getToken(array $params, $forceRefresh = false):string;
}