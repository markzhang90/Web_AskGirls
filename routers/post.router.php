<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 2015/6/14
 * Time: 16:13
 */

$app->get('/post/:post_id', function ($post_id) use ($app) {
    $oPosts = new models\Posts();
    $post_result = $oPosts ->getMyPostById($post_id);
    $app->render('post.html',array('comments' => $post_result['comments'], 'post' => $post_result['post'][0]));


})->name('post');

$app->post('/post/:post_id', function ($post_id) use ($app) {

    $data = $app->request()->post();

    echo(var_dump($data['comment']));
    echo($post_id);


});