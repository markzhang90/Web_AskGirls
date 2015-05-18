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

    public function setStuff() {
        return "hello world!!!";
    }


}