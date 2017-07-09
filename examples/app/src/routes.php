<?php

// external authentication
$this->get('/urls', 'App\Controller\BAEAuthController:getUrls');
$this->get('/token', 'App\Controller\BAEAuthController:getToken');

