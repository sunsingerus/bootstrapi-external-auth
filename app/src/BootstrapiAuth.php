<?php

namespace BootstrapiAuth;

use SocialAuther\SocialAuther;
use SocialAuther\Provider\AuthProviderEnum;

use \InvalidArgumentException;

class BootstrapiAuth implements AuthProviderEnum
{
    protected $auth = null;

    public function __construct(array $config)
    {
        $this->auth = new SocialAuther($config);
    }

    public function auth($params, callable $userCreate)
    {
        if (!$this->auth->initProvider($params)) {
            // can't init auth provider from current parameters
            $this->failUnknownUser();
        }

        if (!$this->auth->authenticate($params)) {
            // can't recognize the user
            $this->failUnknownUser();
        }

        $external_id = $this->auth->getUserId();

        $external_user = UserExternal::where('source', $this->auth->getProvider())->where('source_user_id', $external_id)->first();

        if (!$external_user) {
            // external user is not yet known
            // need to register new user in both
            // 1. external user's list
            // 2. own user's list
            if (is_callable($userCreate)) {
                $userCreate();
            }

            $external_user = new UserExternal();
            $external_user->source = $this->auth->getProvider();
            $external_user->source_id = $external_id;
            $external_user->save();
        }

        return $external_user->id;
    }

    /**
     * Relay unknown methods to auth instance
     *
     * @param $method
     * @param $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        if (method_exists($this->auth, $method)) {
            return call_user_func_array([$this->auth, $method], $params);
        }

        throw new InvalidArgumentException("Unknown method " . __CLASS__ . "->$method()");
    }
}
