<?php

// GET index route
$app->get('/index(/)(:option/?)(:page/?)', function ($option = 0, $page = 1) use ($app) {
    $oPosts = new models\Posts();
    $get_back = $oPosts->getAllPosts($option, $page);
//    echo $get_back['posts'][0]['title'];
    $app->render('index.html', array('posts' => $get_back['posts'],'startpage' => 1, 'endpage' => 10, 'page' => 2, 'totalpage' => 30));
});

$app->get('/about',function () use ($app){

    $app->render('main.html');

});


