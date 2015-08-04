<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 2015/8/1
 * Time: 18:35
 */

$app->post('/load_comments', function () use ($app) {
    $body = json_decode($app->request->getBody());
    $pid =  $body->pid;
    $page_num =  $body->pageNum;
    $page_size = $body->pageSize;
    $oPosts = new models\Posts();
    $post_result = $oPosts ->getCommentsByPage($pid, $page_num, $page_size);
    echo json_encode($post_result);
//    echo $page_num;

});

$app->post('/get_total_page', function () use ($app) {
    $body = json_decode($app->request->getBody());
    $pid = $body->pid;
    $page_size = 10;
    $oPosts = new models\Posts();
    $result = $oPosts ->getCommentsNumByPid($pid);
    $total_records = $result[0]['count(*)'];
    $total_page =ceil($total_records/$page_size);
    echo $total_page;
//    echo $page_num;

});

