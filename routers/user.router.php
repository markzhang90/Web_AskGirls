<?php

// Get user
$app->get('/user', function () use ($app) {

    if (isset($_SESSION['user_id'])) {
        $app->redirect('userview');
    } else {
        $app->redirect('login');
    }
})->name('user');


$app->get('/userview', function () use ($app) {

    if (isset($_SESSION['user_id'])) {
        $get_user = new models\User();
        $get_post = new models\Posts();
        $user_info = $get_user->getUserInfor($_SESSION['user_id'])[0];
        $num_post = $get_post->getNumPostByUid($_SESSION['user_id'])[0];
        $user_info['user_nickname'] = rawurldecode($user_info['user_nickname']);
        $user_info['user_email'] = rawurldecode($user_info['user_email']);

        $num_reply = $get_post->getNumCommentByUid($_SESSION['user_id'])[0];
//        var_dump($user_info);
        $app->render('user_view.html', array('infor' => $user_info, 'post_num' => $num_post, 'reply_num' => $num_reply));
    } else {
        $app->redirect('login');
    }
})->name('userview');


$app->post('/useredit', function () use ($app) {

//    var_dump($_FILES['foo']["size"]);
    if (isset($_SESSION['user_name'])) {
        $success_flag = True;
        if ($_FILES['foo']["size"] != 0) {
            $storage = new Upload\Storage\FileSystem('../user_avatar/', true);
            $file = new \Upload\File('foo', $storage);
// Optionally you can rename the file on upload
            $new_filename = $_SESSION['user_name'];
            $file->setName($new_filename);
            $file->setExtension();
// Validate file upload
            $file->addValidations(array(
                // Ensure file is of type "image/png"
                new \Upload\Validation\Mimetype(array('image/png', 'image/jpeg', 'image/jpg')),
                //You can also add multi mimetype validation
                // Ensure file is no larger than 5M (use "B", "K", M", or "G")
                new Upload\Validation\Size('2M')
            ));
// Access data about the file that has been uploaded
            $data = array(
                'path'=> $file->getPath(),
                'name' => $file->getNameWithExtension(),
                'extension' => $file->getExtension(),
                'mime' => $file->getMimetype(),
                'size' => $file->getSize(),

            );
// Try to upload file
            if(sizeof($data) > 0){
                try {
//                    var_dump($data);
                    // Success!
                    $file->upload();

                } catch (Exception $e) {
                    // Fail!
                    $success_flag = False;
                    $errors = $file->getErrors();
                    var_dump($errors);
                    $app->flash('img_error', $errors);
                }
            }else{
                $success_flag = False;
                $app->flash('img_error', "Image File is not Acceptable");
            }

        }

        $user =  new models\User();
        $data = $app->request()->post();
        $result = $user->updateUser($data, $_SESSION['user_id']);
        if($result && $success_flag){
            $app->flash('correct', "Successfully Updated");
            $app->redirect('useredit');
        }else{
            $app->flash('error', "Update Failed");
            $app->redirect('useredit');
        }

    } else {
        $app->redirect('login');
    }
});

$app->get('/useredit', function () use ($app) {

    if (isset($_SESSION['user_id'])) {
        $get_user = new models\User();
        $user_info = $get_user->getUserInfor($_SESSION['user_id'])[0];
        $error_value = array();
        if (isset($_SESSION['slim.flash']['error'])) {
            $error = $_SESSION['slim.flash']['error'];
            array_push($error_value, $error);
        }else{
            $error = null;
        }

        if (isset($_SESSION['slim.flash']['img_error'])) {
            $img_errors = $_SESSION['slim.flash']['img_error'];
            foreach ($img_errors as $value) {
                array_push($error_value, $value);
            }

        }else{
            $img_error = null;
        }

        if (isset($_SESSION['slim.flash']['correct'])) {
            $correct = $_SESSION['slim.flash']['correct'];
        }else{
            $correct = null;
        }

        $app->render('user_edit.html', array('user' => $user_info, 'error'=>$error_value, 'success'=>$correct));
    } else {
        $app->redirect('login');
    }
})->name('useredit');


$app->get('/list/:option/:page', function ($option, $page = 0) use ($app) {
    if (isset($_SESSION['user_id'])) {
        $get_user = new models\Posts();
        if ($option == 'mylist') {
            $getlist = $get_user->getPostById($_SESSION['user_id'], 0, $page);
//            var_dump($getlist);
        } else if ($option == 'myreply') {
            $getlist = $get_user->getPostById($_SESSION['user_id'], 1, $page);
//            var_dump($getlist);
        } else {
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
        $app->render('user_list.html', array('posts' => $getlist['posts'], 'startpage' => $start_page, 'endpage' => $end_page, 'page' => $current_page, 'totalpage' => $total_page_db, 'option' => $option));

    } else {
        $app->redirect('login');
    }
})->name('userlist');

$app->get('/useraddpost', function () use ($app) {
    if (isset($_SESSION['slim.flash']['useraddpost'])) {
        $add_post_msg = $_SESSION['slim.flash']['useraddpost'];
        $_SESSION['slim.flash']['useraddpost'] = null;
    }else{
        $add_post_msg = null;
    }
    $app->render('user_add_post.html',array( 'addpostmsg' => $add_post_msg));
})->name('useraddpost');


$app->post('/useraddpost', function () use ($app) {
    if (isset($_SESSION['user_id'])) {
        $data = $app->request()->post();
        $data['title'] = rawurlencode($data['title']);
        $data['content'] = rawurlencode($data['content']);
        $new_post = new models\Posts();
        $new_post->addNewPost($data, $_SESSION['user_id']);
        if($new_post){
            $app->redirect('index/0/1');
        }else{
            $app->flash('addpostmsg', "Failed to submit the new post");
            $app->redirect('useraddpost');
        }
    }else{
        $app->redirect('login');
    }

});



