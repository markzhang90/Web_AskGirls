<?php

// GET index route
$app->get('/', function () use ($app) {
    $oStuff = new models\Stuff();
    $users = $oStuff->getAllStuff();
//    print_r($users);
    $app->render('index.html', array('users' => $users));
});

$app->get('/about',function () use ($app){

    $app->render('main.html');

});
