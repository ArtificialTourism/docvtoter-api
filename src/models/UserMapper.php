<?php
class UserMapper extends PHPFrame_Mapper
{
	public function __construct(PHPFrame_Database $db)
    {
        parent::__construct("User", $db, "#__user");
    }
    
    public function findByUsername($username)
    {
        $id_obj = $this->getIdObject();

        $id_obj->where("username","=",":username")
        ->params(":username",$username);

        $user = $this->findOne($id_obj);
        return $user;
    }
    
    public function findByEvent($event)
    {
        $id_obj = $this->getIdObject();
        $table = $id_obj->getTableName();
        
        $id_obj->select($table.".*")
        ->join('JOIN eventusers e ON e.user_id = '.$table.'.id')
        ->where("e.event_id", "=", ":event")
        ->params(":event",$event);
        return $this->find($id_obj);
    }
}