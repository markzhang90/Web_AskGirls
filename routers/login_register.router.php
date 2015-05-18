<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 2015/5/15
 * Time: 15:52
 */
$app->get('/login', function () use ($app) {

    if (isset($_SESSION['slim.flash']['error'])) {
        $error_value = $_SESSION['slim.flash']['error'];
    }else{
        $error_value = null;
    }
    $app->render(
        'login.html',
        array('error'=> $error_value)
    );

})->name('login');




$app->get('/register', function () use ($app) {

    if (isset($_SESSION['slim.flash']['error'])) {
        $error_value = $_SESSION['slim.flash']['error'];
    }else{
        $error_value = null;
    }
    $app->render(
        'register.html',
        array('error'=> $error_value)
    );
})->name('register');


$app->post('/checkuser', function () use($app) {
    $data = $app->request()->post();
    $un = $data['username'];
    $pw = $data['password'];
//    echo($un);
//    echo($pw);
    $get_user = new models\User();
    $result = $get_user->getUserByLogin($un, $pw);
    print_r($result);

    if($result != null){
        $_SESSION['user_id'] = $result[0]['user_id'];
        $_SESSION['user_nickname'] = $result[0]['user_nickname'];
        if (isset($_SESSION['user_id'])) {
            $app->redirect('index/0/1');
        }
    }else{
        $error = "Username or Password not match.";
        $app->flash('error', $error);

        $app->redirect('login');
    }
    //Create book
})->name('checkuser');