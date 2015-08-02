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
    if (isset($_SESSION['user_id'])) {
        $data = $app->request()->post();
        $oPosts = new models\Posts();
        $post_result = $oPosts ->addCommentToPost($data['comment'], $post_id, $_SESSION['user_id']);
        if($post_result){
            $app->urlFor('post', array('post_id' => $post_id));
        }else{
            echo "Failed";
        }
    }else{
        $app->redirect('login');
    }

});

$app->post('/add-comment/:post_id', function($post_id) use ($app){
    if (isset($_SESSION['user_id'])) {
        $data = $app->request()->post();
        $post_instance = new models\Posts();
        echo $post_id;
        var_dump($data);
    }
    else{
        $app->redirect('login');
    }

})->name('addcomment');

