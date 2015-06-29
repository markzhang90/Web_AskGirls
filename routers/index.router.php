<?php

// GET index route
$app->get('/index(/:option/:page)', function ($option = 0, $page = 1) use ($app) {
    $oPosts = new models\Posts();
    $get_back = $oPosts->getAllPosts($option, $page);
//    print_r($get_back);
    $current_page = $get_back['pageNum'];
    $total_page_db = $get_back['totalPage'];
    $options = $get_back['options'];
//    echo($get_back['pageNum']."____");
//    echo($get_back['totalPage']."//");
    $page_length = 7;
    $page_offset = 3;
    $start_page = 0;
    $end_page = 0;
    if ($total_page_db <= $page_length) {
        $end_page = $total_page_db;
        $start_page = 1;
    } else {
        if ($current_page <= $page_offset) {
            $start_page = 1;
        } else {
            if ($current_page + $page_offset >= $total_page_db) {
                $start_page = $total_page_db - $page_length;
            } else {
                $start_page = $current_page - $page_offset;
            }
        }
        if ($start_page + $page_length > $total_page_db) {
            $end_page = $total_page_db;
        } else {
            $end_page = $start_page + $page_length;
        }

    }

    $app->render('list.html', array('posts' => $get_back['posts'], 'startpage' => $start_page, 'endpage' => $end_page, 'page' => $current_page, 'totalpage' => $total_page_db, 'option'=> $options));
})->name('index');

$app->get('/about', function () use ($app) {

    $app->render('main.html');

});




