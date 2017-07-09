<?php

namespace App\Controller;

use App\Requests\GetTokenRequest;
use App\Model\User;
use App\Model\AccessToken;
use App\Model\RefreshToken;

use PHPUnit\Runner\Exception;
use Slim\Http\Request;
use Slim\Http\Response;

use App\Common\JsonException;
use BAEAuth\UserExternal;

class BAEAuthController extends TokenController
{

    /**
     * @api {get} /urls Внешняя аутентификация
     * @apiName Auth
     * @apiGroup Token
     *
     * @apiDescription Метод для получения списка URL по которым может быть проведена аутентификация
     * <br/>
     *
     * @apiSuccessExample {json} Успешно (200)
     *     HTTP/1.1 200 OK
     *     {
     *       "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOmZhbHNlLCJhdWQiOiJza2VsZXRvbi5kZXYiLCJpYXQiOjE0NzY0Mjk4NjksImV4cCI6MTQ3NjQzMzQ2OX0.NJn_-lK28kEZyZqygLr6B-FZ2zC2-1unStayTGicP5g",
     *       "expires_in": 3600,
     *       "token_type": "Bearer",
     *       "refresh_token": "092ea7e36f6b9bf462cb3ca1f6f86b80"
     *     }
     *
     * @apiUse StandardErrors
     */
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function getUrls(Request $request, Response $response)
    {
        $auth = new BAEAuth($this->settings['auth']);

        return $this->apiRenderer->jsonResponse($response, 200, json_encode($auth->getProvidersInfo()));
    }

    /**
     * @api {get} /auth Внешняя аутентификация
     * @apiName Auth
     * @apiGroup Token
     *
     * @apiDescription Метод для получения авторизационного токена. Токен необходим для выполнения запросов к АПИ.
     * Полученный токен отправляется в заголовке запроса:
     * <br/>
     * <strong>Authorization: Bearer xxxxxxxxxxxxxxxxxxxxxxxxxxxxx</strong>
     *
     * @apiHeader {String} Content-Type application/vnd.api+json <br/> application/json
     *
     * @apiSuccessExample {json} Успешно (200)
     *     HTTP/1.1 200 OK
     *     {
     *       "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOmZhbHNlLCJhdWQiOiJza2VsZXRvbi5kZXYiLCJpYXQiOjE0NzY0Mjk4NjksImV4cCI6MTQ3NjQzMzQ2OX0.NJn_-lK28kEZyZqygLr6B-FZ2zC2-1unStayTGicP5g",
     *       "expires_in": 3600,
     *       "token_type": "Bearer",
     *       "refresh_token": "092ea7e36f6b9bf462cb3ca1f6f86b80"
     *     }
     *
     * @apiUse StandardErrors
     */
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function getToken(Request $request, Response $response)
    {
        $auth = new BAEAuth($this->settings['auth']);
        $params = $request->getQueryParams();
        try {
            $user = User::find($auth->auth($params, null));
        } catch (Exception $ex) {

        }

        if ($user) {
            return $this->buildTokens($request, $response, $user);
        } else {
            $this->failUnknownUser();
        }
    }
}
