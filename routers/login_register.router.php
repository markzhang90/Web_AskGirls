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



$app->post('/checkuser', function () use($app) {
    $data = $app->request()->post();
    $un = $data['username'];
    $pw = $data['password'];
//    echo($un);
//    echo($pw);
    $get_user = new models\User();
    $result = $get_user->getUserByLogin($un, $pw);
//    print_r($result);

    if($result != null){
        $_SESSION['user_id'] = $result[0]['user_id'];
        $_SESSION['user_name'] = $result[0]['user_name'];
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



$app->get('/register', function () use ($app) {

    if (isset($_SESSION['slim.flash']['register_error'])) {
        $error_value = $_SESSION['slim.flash']['register_error'];
    }else{
        $error_value = null;
    }

    if (isset($_SESSION['slim.flash']['previous_data'])) {
        $previous_data = $_SESSION['slim.flash']['previous_data'];
    }else{
        $previous_data = null;
    }

    $app->render(
        'register.html',
        array('error'=> $error_value,'data'=>$previous_data)
    );
})->name('register');

$app->post('/register', function () use($app) {
    $get_user = new models\User();
    $data = $app->request()->post();
    $error_list = array();
    if(empty($data['check_rule'])){
        $error_list['checkbox'] = "Please agree the statement by clicking the checkbox";
    }
    if($data['gender'] == -1 || empty($data['gender'])){
        $error_list['gender'] = "Please pick a gender";
    }


    if(empty($data['nick_name']) || $data['nick_name'] == "" || $data['nick_name'] == " "){
        $error_list['nick_name'] = "Please Put a valid Nickname";
    }

    if(empty($data['user_name']) || $data['user_name'] == "" || $data['user_name'] == " "){
        $error_list['user_name'] = "User name cant be EMPTY";
    }else if($get_user->checkUserByUsername($data['user_name'])){
        $error_list['user_name'] = "User name already exist";
    }

    if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
        $error_list['email'] = "Email format is not correct";
    }else if($get_user->checkUserByEmail($data['email'])){
        $error_list['email'] = "Email Address already exist";
    }

    if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{6,12}$/', $data['password'])) {
        $error_list['password'] = "the password does not meet the requirements! At least one letter and at least 6 digits";
    }else if($data['password'] != $data['password_confirmation']){
        $error_list['password'] = "Password Does Not MATCH";
    }

    if(empty($error_list)){
        $result =  $get_user->insertUser($data);
        if($result != 0){
            $_SESSION['user_id'] = $result;
            $_SESSION['user_nickname'] = $data['nick_name'];
            if (isset($_SESSION['user_id'])) {
                $app->redirect('index/0/1');
            }
        }
        var_dump($data);
    }else{
        $app->flash('register_error', $error_list);
        $app->flash('previous_data', $data);
        $app->redirect('register');
    }

});
