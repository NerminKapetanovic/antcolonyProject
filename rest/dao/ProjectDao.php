<?php
require_once __DIR__.'/BaseDao.php';

class ProjectDao extends BaseDao {

  /**
  * constructor of dao class
  */
  public function __construct(){
    parent::__construct("projects");
  }

  public function get_all(){
    $user=Flight::get('user');
    $stmt = $this->conn->prepare("SELECT * FROM projects WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user['id']]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function get_by_id($id){
    $stmt = $this->conn->prepare("SELECT * FROM projects WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return reset($result);
  }

  /**
  * Delete todo record from the database
  */
  public function delete($id){
    $stmt = $this->conn->prepare("DELETE FROM projects WHERE id=:id");
    $stmt->bindParam(':id', $id); // SQL injection prevention
    $stmt->execute();
  }

  public function add($entity){
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

  public function update($id, $entity, $id_column = "id"){
   
    $query = "UPDATE projects  SET ";
    foreach($entity as $name => $value){
      $query .= $name ."= :". $name. ", ";
    }
    $query = substr($query, 0, -2);
    $query .= " WHERE ${id_column} = :id";
 
    $stmt= $this->conn->prepare($query);
    $entity['id'] = $id;
    $stmt->execute($entity);
    return $entity;
  }

 

}

?> 