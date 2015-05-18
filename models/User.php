<?php

/**
 * This is an example of User Class using PDO
 *
 */

namespace models;
use lib\Core;
use PDO;

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

	// Get user by the Id
	public function getUserById($id) {
		$r = array();		
		
		$sql = "SELECT numbre * evnt_usuario WHERE id=$id";
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

		$sql = "SELECT * FROM user WHERE user_name='$username' AND user_passwd='$pass'";
		$stmt = $this->core->dbh->prepare($sql);

		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	// Insert a new user
	public function insertUser($data) {
		try {
			$sql = "INSERT INTO user (name, email, password, role) 
					VALUES (:name, :email, :password, :role)";
			$stmt = $this->core->dbh->prepare($sql);
			if ($stmt->execute($data)) {
				return $this->core->dbh->lastInsertId();;
			} else {
				return '0';
			}
		} catch(PDOException $e) {
        	return $e->getMessage();
    	}
		
	}

	// Update the data of an user
	public function updateUser($data) {
		
	}

	// Delete user
	public function deleteUser($id) {
		
	}

}