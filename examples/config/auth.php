<?php

use BAEAuth\BAEAuth;

//Auth
return [
    'auth' => [
        BAEAuth::PROVIDER_VKONTAKTE => array(
            'client_id'     => '6097024',
            'client_secret' => 'Fzo6IqM8fMczAXycXpFl',
            'redirect_uri'  => 'http://localhost/examples/auth.php?provider=' . BAEAuth::PROVIDER_VKONTAKTE,
        ),
    ],
];