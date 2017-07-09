<?php

use BAEAuth\BAEAuth;

//Auth
return [
    'auth' => [
        BAEAuth::PROVIDER_VKONTAKTE => array(
            'client_id'     => '6054585',
            'client_secret' => 'E725K1saU2j3OAQsz7lX',

            'redirect_uri'  => 'http://mcw.domain/api/token?provider=' . BAEAuth::PROVIDER_VKONTAKTE,
//            'redirect_uri'  => 'http://localhost/examples/auth.php?provider=' . BAEAuth::PROVIDER_VKONTAKTE,
        ),
    ],
];