<?php
require_once __DIR__.'/BaseDao.php';

class UserDao extends BaseDao{

  /**
  * constructor of dao class
  */
    public function __construct(){
    parent::__construct("users");
    }

    public function get_user_by_email($email){
        return $this->query_unique("SELECT * FROM users WHERE email = :email", ['email' => $email]);
    }

    public function register($entity){
        $entity['password']=md5($entity['password']);
        $query = "INSERT INTO ".$this->table_name." (";
        foreach ($entity as $column => $value) {
            $query .= $column.", ";
        }
        $query = substr($query, 0, -2);
        $query .= ") VALUES (";
        foreach ($entity as $column => $value) {
            $query .= ":".$column.", ";
        }
        $query = substr($query, 0, -2);
        $query .= ")";

        $stmt= $this->conn->prepare($query);
        $stmt->execute($entity); // sql injection prevention
        $entity['id'] = $this->conn->lastInsertId();
        return $entity;
    }

}

?>