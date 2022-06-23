<?php

namespace sso\Dispatch;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache;
use sso\Dispatch\Exception\SsoException;

class Token extends Api
{
    /**
     * Response Json key name.
     *
     * @var string
     */
    protected $tokenJsonKey = 'token';
    /**
     * Cache.
     *
     * @var Cache
     */
    protected $cache;
    /**
     * {@inheritdoc}.
     */
    protected $prefix = 'sso.common.program.access_token.';
    /**
     * Cache Key.
     *
     * @var string
     */
    protected $cacheKey;

    /**获取token
     * @throws SsoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTokenInfo(array $params)
    {
        $data = $this->request('/oauth2/v2/token', $params);
        return $data['data'];
    }

    /**
     * Get token from WeChat API.
     *
     * @param bool $forceRefresh
     *
     * @return string
     */
    public function getToken(array $params, $forceRefresh = false): string
    {
        $cacheKey = $this->getCacheKey();
        $cached = $this->getCache()->fetch($cacheKey);
        if ($forceRefresh || empty($cached)) {
            if (empty($params['code'])) {
                throw new SsoException('参数错误请重新获取授权码', 500);
            }
            $token = $this->getTokenInfo($params);
            // XXX: T_T... 7200 - 1500
            $this->getCache()->save($cacheKey, $token[$this->tokenJsonKey], $token['expiresIn'] - 500);
            return $token[$this->tokenJsonKey];
        }
        return $cached;
    }

    /**
     * Return the cache manager.
     *
     * @return \Doctrine\Common\Cache\Cache
     */
    public function getCache()
    {
        return $this->cache ?: $this->cache = new FilesystemCache(sys_get_temp_dir());
    }

    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Get access token cache key.
     *
     * @return string $this->cacheKey
     */
    public function getCacheKey()
    {
        if (is_null($this->cacheKey)) {
            return $this->prefix . $this->appKey;
        }

        return $this->cacheKey;
    }

    /**获取token
     * @throws SsoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function loginOut()
    {
        return $this->request('/authz/logout', []);
    }
    public function getRedirectUrl(){
        return $this->jumpUrl.'?appId='.$this->appKey;
    }

    /**获取用户信息
     * @throws SsoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function userInfo()
    {
        return $this->request('/authz/info', [], "GET", true);
    }

    /**获取用户扩展信息
     * @throws SsoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function userInfoExtend()
    {
        return $this->request('/authz/info', [], "GET", true);
    }

    /**获取角色
     * @param array $params
     * @return mixed|void
     * @throws \Dq\DqDispatch\Exception\DqDispatchException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRoles()
    {
        return $this->request('/authz/roles', [], "GET", true);
    }

    /**获取用户路由列表
     * @param array $params
     * @return mixed|void
     * @throws \Dq\DqDispatch\Exception\DqDispatchException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRoutes()
    {
        return $this->request('/authz/routes', [], "GET", true);
    }

    /**获取用户权限列表
     * @param array $params
     * @return mixed|void
     * @throws \Dq\DqDispatch\Exception\DqDispatchException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPerms()
    {
        return $this->request('/authz/perms', [], "GET", true);
    }
}