<?php
require_once __DIR__.'/BaseService.php';
require_once __DIR__.'/../dao/ProjectDao.php';
require_once __DIR__.'/../dao/UserDao.php';

 class ProjectService extends BaseService {

    public function __construct(){
        parent::__construct(new ProjectDao());
    }

    public function get_all(){
        return $this->dao->get_all();
    }

    public function get_by_id($id){
        return $this->dao->get_by_id($id);
    }

    public function add($entity){
        return $this->dao->add($entity);
    }

    public function update($id, $entity){
        return $this->dao->update($id, $entity);
    }

    public function delete($id){
        return $this->dao->delete($id);
    }
}
?>