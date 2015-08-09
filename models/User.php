<?php

/**
 * This is an example of User Class using PDO
 *
 */

namespace models;
use lib\Core;
use PDO;
use lib\Config;
// <= php 5.4
//require '../lib/password.php';
class User {

	protected $core;

	function __construct() {
		$this->core = Core::getInstance();
		//$this->core->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	// Get all users
	public function getUsers() {
		$r = array();		

		$sql = "SELECT * FROM user";
		$stmt = $this->core->dbh->prepare($sql);
		//$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
		} else {
			$r = 0;
		}		
		return $r;
	}


	// Get user by the Login
    /**
     * @param $username
     * @param $pass
     * @return array|int
     */
    public function getUserByLogin($username, $pass) {
		$r = array();

		$sql = "SELECT * FROM user WHERE user_name='$username'";
		$stmt = $this->core->dbh->prepare($sql);

		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$r = 0;
		}		
		return $r;
	}

    // Get user info
    public function getUserInfor($id) {
        $r = array();

        $sql = "SELECT * FROM user WHERE user_id = '$id'";
        $stmt = $this->core->dbh->prepare($sql);

        if ($stmt->execute()) {
            $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $r = NULL;
        }

        return $r;
    }


    // Insert a new user
	public function insertUser($data) {
        $nickname = $data['nick_name'];
        $username = $data['user_name'];
        $gender = $data['gender'];
        $email = $data['email'];
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
		try {
			$sql = "INSERT INTO user (user_nickname, user_name, gender, user_passwd, user_email)
					VALUES ('$nickname', '$username','$gender', '$password','$email')";
			$stmt = $this->core->dbh->prepare($sql);
			if ($stmt->execute($data)) {
				return $this->core->dbh->lastInsertId();
			} else {
				return '0';
			}
		} catch(PDOException $e) {
        	return $e->getMessage();
    	}

	}


    public function checkUserByUsername($data)
    {
        try {
            $sql = "SELECT * FROM user WHERE user_name='$data'";
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
            $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($r) {
                return true;
            } else {
                return false;
            }

        }catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function checkUserByEmail($data)
    {
        try {
            $sql = "SELECT * FROM user WHERE user_email='$data'";
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
            $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($r) {
                return true;
            } else {
                return false;
            }

        }catch(PDOException $e) {
            return $e->getMessage();
        }
    }

	// Update the data of an user
	public function updateUser($data, $user_id, $isChangeImage) {
        $nickname = $data['user_nickname'];
        $icon_image_string = "";
        if($isChangeImage){
//            $host_name = Config::read('remote_server');
            $image_link = "http://localhost/Web_AskGirls/user_avatar/".$data['file_name'];
            $icon_image_string = " , icon_image='$image_link'";
        }
        $passwd_string = "";
        if(sizeof($data['user_passwd']) > 0){
            $passwd = password_hash($data['user_passwd'], PASSWORD_DEFAULT);
            $passwd_string = " , user_passwd='$passwd'";
        }
        $sql = "UPDATE user SET user_nickname='$nickname'" . $passwd_string . $icon_image_string ."  WHERE user_id='$user_id'";
//        return $sql;
        try {

            $stmt = $this->core->dbh->prepare($sql);
            if ( $stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }catch(PDOException $e) {
            $e->getMessage();
            return false;
        }
	}

	// Delete user
	public function deleteUser($id) {
		
	}

}