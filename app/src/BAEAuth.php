<?php

namespace BAEAuth;

use SocialAuth\SocialAuth;
use SocialAuth\Provider\AuthProviderEnum;
use BAEAuth\Model\UserExternal;

use \InvalidArgumentException;

class BAEAuth implements AuthProviderEnum
{
    protected $auth = null;

    /**
     * BAEAuth constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->auth = new SocialAuth($config);
    }

    /**
     * Main function
     *
     * @param string $provider - name/id of the provider (string)
     * @param array $params - array of params provided by external source for authentication
     * @param callable $createUser - function to create BA's user in case this is first time authentication
     *
     * @return int id of local user referenced by external user
     */
    public function auth($provider, array $params, callable $createUser)
    {
        if (!$this->auth->initProvider($provider)) {
            // can't init auth provider from current parameters
            return null;
        }

        if (!$this->auth->authenticate($params)) {
            // can't recognize the user
            return null;
        }

        // external user id provided by external source (string)
        $external_id = $this->auth->getUserId();
        // external auth source name (string)
        $source = $this->auth->getProvider()->getProvider();
        // find instance of local representation of this external user
        $external_user = UserExternal::where('source', $source)->where('source_user_id', $external_id)->first();

        if (!$external_user) {
            // external user is not yet known locally - need to register new user in both:
            // 1. external user's list
            // 2. own user's list
            $user = null;
            if (is_callable($createUser)) {
                // create our internal user - referenced by this external user
                $user_id = $createUser($this->auth->getUserInfo());
            }

            // need to have User instance in order to proceed
            if (empty($user_id)) {
                return null;
            }

            // create local representation of this external user
            $external_user = new UserExternal();
            $external_user->source = $source;
            $external_user->source_user_id = $external_id;
            $external_user->user_id = $user_id;
            $external_user->save();
        }

        return $external_user->user_id;
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
