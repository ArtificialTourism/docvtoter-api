<?php
class EventMapper extends PHPFrame_Mapper
{
	public function __construct(PHPFrame_Database $db)
    {
        parent::__construct("Event", $db, "#__event");
    }
    
    public function findByUser($user)
    {
        $id_obj = $this->getIdObject();
        $table = $id_obj->getTableName();
        
        $id_obj->select($table.".*")
        ->join('JOIN eventuser e ON e.event_id = '.$table.'.id')
        ->where("e.user_id", "=", ":user")
        ->params(":user",$user);
        return $this->find($id_obj);
    }
}