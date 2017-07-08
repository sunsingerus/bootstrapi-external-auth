<?php

// external authentication
$this->get('/urls', 'App\Controller\AuthController:getUrls');
$this->get('/token', 'App\Controller\AuthController:getToken');

