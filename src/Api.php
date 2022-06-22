<?php

namespace sso\Dispatch;

use Dq\DqDispatch\Exception\DqDispatchException;
use GuzzleHttp\Exception\RequestException;
use Hanson\Foundation\AbstractAPI;
use GuzzleHttp\Handler\CurlHandler;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\HandlerStack;

class Api extends AbstractAPI
{


    public $appKey;
    public $appSecret;
    public $gatewayUrl = "";

    public function __construct(array $config)
    {
        $this->appKey = $config['appKey'];
        $this->appSecret = $config['appSecret'];
        $this->gatewayUrl = $config['gatewayUrl'];
    }

    public function getMillisecond()
    {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }

    /**
     * @throws DqDispatchException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(string $method, array $params, string $httpMethod = 'POST')
    {
        $t = $this->getMillisecond();
        $headerArr['token-auth'] = ' basic ' . $this->signature($t);
        $headerArr['ts'] = $t;
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
            throw new DqDispatchException('返回返回数据为空', 500);
        }
    }

    public function signature($t): string
    {
        return base64_encode($this->appKey . ":" . sha1($t . $this->appSecret));
    }
}