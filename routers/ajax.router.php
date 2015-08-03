<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 2015/8/1
 * Time: 18:35
 */

$app->post('/load_comments/:post_id', function ($post_id) use ($app) {

    $oPosts = new models\Posts();
    $post_result = $oPosts ->getMyPostById($post_id);
    echo var_dump($post_result);


});
