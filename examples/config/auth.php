<?php

use BootstrapiAuth\BootstrapiAuth;

//Auth
return [
    'auth' => [
        BootstrapiAuth::PROVIDER_VKONTAKTE => array(
            'client_id'     => '6097024',
            'client_secret' => 'Fzo6IqM8fMczAXycXpFl',
            'redirect_uri'  => 'http://localhost/examples/auth.php?provider=' . BootstrapiAuth::PROVIDER_VKONTAKTE,
        ),
    ],
];