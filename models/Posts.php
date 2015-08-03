<?php
/**
 * Created by PhpStorm.
 * User: Mark
 * Date: 5/5/15
 * Time: 2:00 PM
 */

namespace models;
use lib\Core;
use PDO;

class Posts {

    protected $core;

    function __construct() {
        $this->core = \lib\Core::getInstance();
    }

    public function getNumPostByUid($id){
        $query = "SELECT count(*) from publishes where user_id = '$id'";
        $stmt = $this->core->dbh->prepare($query);
        if ($stmt->execute()) {
            $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $r = 0;
        }
        return $r;
    }

    public function getNumCommentByUid($id){
        $query = "SELECT count(*) from comments where user_id = '$id'";
        $stmt = $this->core->dbh->prepare($query);
        if ($stmt->execute()) {
            $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $r = 0;
        }
        return $r;
    }

    public function addNewPost($post_data, $user_id){
        $title = $post_data['title'];
        $area = $post_data['bt-choice'];
        $content = $post_data['content'];
        try {
            $query = "INSERT INTO publishes (user_id,areaid,title, content) VALUES ( '$user_id', '$area', '$title', '$content')";
            $stmt = $this->core->dbh->prepare($query);
            if ($stmt->execute()) {
                return True;
            } else {
                return False;
            }
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    // Get all stuff
    public function getAllPosts($options, $pageNum) {
        $page_Size = 5;

        if($options == 0){
            $sql = "SELECT pid from publishes";
        }else{
            $sql = "SELECT pid from publishes where areaid = $options";
        }

        $stmt = $this->core->dbh->prepare($sql);

        if ($stmt->execute()) {
            $total = $stmt->rowCount();
        } else {
            $total = 0;
        }
        $totalPage = ceil($total/$page_Size);
        $startPosition = ($pageNum-1)*$page_Size;
        $result_array = array();

        $result_array['total'] = $total;
        $result_array['pageSize'] = $page_Size;
        $result_array['totalPage'] = $totalPage;
        $result_array['options'] = $options;
        $result_array['pageNum'] = $pageNum;
        $query_Z = "SELECT U.user_id, U.user_nickname, U.gender, U.icon_image, P.pid, P.title, P.content, P.image_link, COUNT( C.cid ) as ct
		FROM publishes AS P LEFT JOIN user AS U ON P.user_id = U.user_id
		LEFT JOIN comments AS C ON C.pid = P.pid GROUP BY P.pid ORDER BY pid DESC limit $startPosition, $page_Size";
        $query_nonZ = "SELECT U.user_id, U.user_nickname, U.gender, U.icon_image, P.pid, P.title, P.content, P.image_link, COUNT( C.cid ) as ct
		FROM publishes AS P LEFT JOIN user AS U ON P.user_id = U.user_id
		LEFT JOIN comments AS C ON C.pid = P.pid WHERE P.areaid = '$options' GROUP BY P.pid ORDER BY pid DESC limit $startPosition, $page_Size";

        if($options == 0){
            $get_query = $query_Z;

        }else{
            $get_query = $query_nonZ;
        }

        $stmt_get = $this->core->dbh->prepare($get_query);

        $stmt_get->execute();
        $result = $stmt_get->fetchAll();
        $list_temp = array();
        $total_list = array();
        foreach ($result as $one_record){
            $list_temp['user_id'] = $one_record['user_id'];
            $list_temp['user_nickname'] = rawurldecode($one_record['user_nickname']);
            $list_temp['gender'] = $one_record['gender'];
            $list_temp['icon_image'] = rawurldecode($one_record['icon_image']);
            $list_temp['pid'] = $one_record['pid'];
            $list_temp['title'] = rawurldecode($one_record['title']);
            $list_temp['ct'] = $one_record['ct'];
            array_push($total_list, $list_temp);

        }
        $result_array['posts'] = $total_list;
//        print(var_dump($result_array));
        return $result_array;
    }

    public function getPostById($id, $option, $pageNum){
        $page_Size = 5;

        switch (true){
            case ($option == 0 && !empty($id)):
                $countquery ="SELECT U.user_id, U.user_nickname, U.gender, U.icon_image, P.pid, P.title, P.content, P.image_link, COUNT( C.cid ) as ct
		FROM publishes AS P LEFT JOIN user AS U ON P.user_id = U.user_id
		LEFT JOIN comments AS C ON C.pid = P.pid WHERE  P.user_id = '$id' GROUP BY P.pid ORDER BY pid DESC";
                break;
            case ($option == 1 && !empty($id)):
                $countquery = "SELECT U.user_id, U.user_nickname, U.gender, U.icon_image, P.pid, P.title, P.content, P.image_link, COUNT( C.cid ) as ct
		FROM publishes AS P LEFT JOIN user AS U ON P.user_id = U.user_id
		LEFT JOIN comments AS C ON C.pid = P.pid WHERE  C.user_id = '$id' GROUP BY P.pid ORDER BY pid DESC ";
                break;
            default:
                break;
        }

        if(!empty($countquery)) {

            $stmt_get = $this->core->dbh->prepare($countquery);
            $stmt_get->execute();
            $total = $stmt_get->rowCount();

            $totalPage = ceil($total/$page_Size);
            $startPosition = ($pageNum-1)*$page_Size;
            $result_array = array();

            $result_array['total'] = $total;
            $result_array['pageSize'] = $page_Size;
            $result_array['totalPage'] = $totalPage;
            $result_array['pageNum'] = $pageNum;
            $list_temp = array();
            $total_list = array();

            switch (true){
                case ($option == 0 && !empty($id)):
                    $query ="SELECT U.user_id, U.user_nickname, U.gender, U.icon_image, P.pid, P.title, P.content, P.image_link, COUNT( C.cid ) as ct
		FROM publishes AS P LEFT JOIN user AS U ON P.user_id = U.user_id
		LEFT JOIN comments AS C ON C.pid = P.pid WHERE  P.user_id = '$id' GROUP BY P.pid ORDER BY pid DESC limit $startPosition, $page_Size";
                    break;
                case ($option == 1 && !empty($id)):
                    $query = "SELECT U.user_id, U.user_nickname, U.gender, U.icon_image, P.pid, P.title, P.content, P.image_link, COUNT( C.cid ) as ct
		FROM publishes AS P LEFT JOIN user AS U ON P.user_id = U.user_id
		LEFT JOIN comments AS C ON C.pid = P.pid WHERE  C.user_id = '$id' GROUP BY P.pid ORDER BY pid DESC limit $startPosition, $page_Size";
                    break;
                default:
                    break;
            }
            $stmt_get = $this->core->dbh->prepare($query);
            $stmt_get->execute();
            $result = $stmt_get->fetchAll();
            foreach ($result as $one_record){
                $list_temp['user_id'] = $one_record['user_id'];
                $list_temp['user_nickname'] = rawurldecode($one_record['user_nickname']);
                $list_temp['gender'] = $one_record['gender'];
                $list_temp['icon_image'] = rawurldecode($one_record['icon_image']);
                $list_temp['pid'] = $one_record['pid'];
                $list_temp['title'] = rawurldecode($one_record['title']);
                $list_temp['ct'] = $one_record['ct'];
                array_push($total_list, $list_temp);
            }
            $result_array['posts'] = $total_list;
            return $result_array;
        }else{
            return null;
        }
    }

    public function getMyPostById($post_id){

        $result_array = array();
        $comments_list = array();
        $post_list = array();
        $query_post = "SELECT U.user_id, U.user_nickname, U.gender, U.icon_image, P.pid, P.title, P.content, P.image_link as ct
		FROM publishes AS P LEFT JOIN user AS U ON P.user_id = U.user_id WHERE  P.pid = '$post_id'";
        $query_comments = "SELECT C.content, C.row, C.time, C.agree_num, C.disagree_num, U.user_nickname, U.icon_image
                  from publishes as P left join comments as C on P.pid = C.pid left join user as U on C.user_id = U.user_id
                  where P.pid = '$post_id'ORDER BY time DESC ";
        $stmt = $this->core->dbh->prepare($query_comments);
        if ($stmt->execute()) {
            $result = $stmt->fetchAll();
            foreach ($result as $one_record){
                $list_temp['row'] = $one_record['row'];
                $list_temp['content'] = rawurldecode($one_record['content']);
                $list_temp['time'] = $one_record['time'];
                $list_temp['icon_image'] = rawurldecode($one_record['icon_image']);
                $list_temp['agree_num'] = $one_record['agree_num'];
                $list_temp['user_nickname'] = rawurldecode($one_record['user_nickname']);
                $list_temp['disagree_num'] = $one_record['disagree_num'];
                array_push($comments_list, $list_temp);
            }
            $result_array['comments'] = $comments_list;
        } else {
            $result_array['comments'] = null;
        }
        $stmt = $this->core->dbh->prepare($query_post);
        if ($stmt->execute()) {
            $result = $stmt->fetchAll();
            foreach ($result as $one_record){
                $list_temp['user_id'] = $one_record['user_id'];
                $list_temp['content'] = rawurldecode($one_record['content']);
                $list_temp['gender'] = $one_record['gender'];
                $list_temp['icon_image'] = rawurldecode($one_record['icon_image']);
                $list_temp['pid'] = $one_record['pid'];
                $list_temp['user_nickname'] = rawurldecode($one_record['user_nickname']);
                $list_temp['title'] = rawurldecode($one_record['title']);
                array_push($post_list, $list_temp);
            }
            $result_array['post'] = $post_list;
        } else {
            $result_array['comments'] = null;
        }

        return $result_array;
    }

    public function addCommentToPost($comment, $post_id, $user_id){

        try {
            $query = "INSERT INTO comments (pid,content,user_id) VALUES ( '$post_id', '$comment', '$user_id')";
            $stmt = $this->core->dbh->prepare($query);
            if ($stmt->execute()) {
                return True;
            } else {
                return False;
            }
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }



}