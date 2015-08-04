<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 2015/6/14
 * Time: 16:13
 */

$app->get('/post/:post_id', function ($post_id) use ($app) {
    if (isset($_SESSION['slim.flash']['comment_msg'])) {
        $comment_msg = $_SESSION['slim.flash']['comment_msg'];
        $_SESSION['slim.flash']['comment_msg'] = null;
    }else{
        $comment_msg = null;
    }
    $oPosts = new models\Posts();
    $post_result = $oPosts ->getMyPostById($post_id);
//    var_dump( $post_result['comments']);
    $app->render('post.html',array( 'post' => $post_result['post'][0], 'comment_msg' => $comment_msg));


})->name('post');

$app->post('/post/:post_id', function ($post_id) use ($app) {
    if (isset($_SESSION['user_id'])) {
        $data = $app->request()->post();
        $data['comment'] = rawurlencode($data['comment']);
        $oPosts = new models\Posts();
        $post_result = $oPosts ->addCommentToPost($data['comment'], $post_id, $_SESSION['user_id']);
        if($post_result){
            $app->flash('comment_msg', 1);
            $app->redirect($app->urlFor('post', array('post_id' => $post_id)));
        }else{
            $app->flash('comment_msg', 0);
            $app->redirect($app->urlFor('post', array('post_id' => $post_id)));
        }
    }else{
        $app->redirect($app->urlFor('login'));
    }

});



