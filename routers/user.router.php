<?php

// Get user
$app->get('/user', function () use ($app) {	

    if(isset($_SESSION['user_id'])){
        $app->render('user_edit.html');
    }else{
        $app->redirect('login');
    }
})->name('user');


$app->get('/list/:option/:page', function ($option, $page = 0) use ($app) {
    if(isset($_SESSION['user_id'])){
        $get_user = new models\Posts();
        if($option == 'mylist'){
            $getlist = $get_user->getPostById($_SESSION['user_id'], 0,$page);
//            var_dump($getlist);
        }else if($option == 'myreply'){
            $getlist = $get_user->getPostById($_SESSION['user_id'], 1,$page);
//            var_dump($getlist);
        }else{
            $app->redirect('user_edit.html');
        }
        $current_page = $getlist['pageNum'];
        $total_page_db = $getlist['totalPage'];
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
        $app->render('user_list.html', array('posts' => $getlist['posts'], 'startpage' => $start_page, 'endpage' => $end_page, 'page' => $current_page, 'totalpage' => $total_page_db, 'option'=>$option));

    }else{
        $app->redirect('login');
    }
})->name('userlist');

////Create user
//$app->post('/user', function () use ($app) {
//	//var_dump($app->request()->post('data'));
//	$user = json_decode($app->request()->post('data'), true);
//	$user['password'] = hash("sha1", $user['password']);
//	$oUser = new User ();
//	echo $oUser->insertUser($user);
//});
//
//// LOGIN GET user by email and passwordS
//$app->post('/login', function () use ($app) {
//	//var_dump($app->request()->post('data'));
//	$data = json_decode($app->request()->post('data'), true);
//
//	//echo $data['password'];
//	$email = $data['email'];
//	$pass = hash("sha1", $data['password']);
//	//echo "  despues: ".$pass. "   ";
//
//	$oUser = new User();
//
//	echo json_encode($oUser->getUserByLogin($email, $pass), true);
//});
//
//// PUT route
//$app->put('/user', function () {
//	echo 'This is a PUT route';
//});
//
//// DELETE route
//$app->delete('/user', function () {
//    echo 'This is a DELETE route';
//});