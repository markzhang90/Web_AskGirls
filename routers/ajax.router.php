<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 2015/8/1
 * Time: 18:35
 */

$app->get('/check_session', function () use ($app) {

    $app->render('main.html');

});
