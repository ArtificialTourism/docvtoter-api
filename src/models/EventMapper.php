<?php
class EventMapper extends PHPFrame_Mapper
{
	private $_include_owner = false;
	private $_include_card_count = false;
	private $_user_mapper, $_eventcard_mapper;
	private $_db;
	
	public function __construct(PHPFrame_Database $db)
    {
        parent::__construct("Event", $db, "#__event");
        
        $this->_user_mapper = new UserMapper($db);
        $this->_eventcard_mapper = new PHPFrame_Mapper('eventcards',$db);
        
        $this->_db = $db;
    }
    
    public function find(PHPFrame_IdObject $id_obj=null)
    {
        if(!isset($id_obj)) {
            $id_obj = $this->getIdObject();
        }
        $collection = parent:: find($id_obj);

        foreach ($collection as $event) {
            if ($this->_include_owner) {
                $owner = $this->_user_mapper->findOne($event->owner());
                $event->ownerUser($owner);
            }
            
            if ($this->_include_card_count && $event->id()) {
            	$id_obj = $this->_eventcard_mapper->getIdObject();
            	$id_obj->select("COUNT(*)")
            	->where("event_id","=",":event_id");
            	$params = array(":event_id"=>$event->id());
            	$sql = $id_obj->getSQL();
                $assoc = $this->_db->fetchAssoc($sql, $params);
            	$count = $assoc['COUNT(*)'];
            	$event->cardCount($count);
            }
        }

        return $collection;
    }
    
    public function findOne($id_obj)
    {
        $event = parent:: findOne($id_obj);

        if($event && $event->id()) {
            if ($this->_include_owner) {
                $owner = $this->_user_mapper->findOne($event->owner());
                $event->ownerUser($owner);
            }
            
            if ($this->_include_card_count) {
                $id_obj = $this->_eventcard_mapper->getIdObject();
                $id_obj->select("COUNT(*)")
                ->where("event_id","=",":event_id");
                $params = array(":event_id"=>$event->id());             
                $sql = $id_obj->getSQL();
                $assoc = $this->_db->fetchAssoc($sql,$params);
                $count = $assoc['COUNT(*)'];
                $event->cardCount($count);
            }
            
        }
        
        return $event;
    }
    
    public function findByUser($user)
    {
        $id_obj = $this->getIdObject();
        $table = $id_obj->getTableName();
        
        $id_obj->select($table.".*")
        ->join('JOIN eventusers e ON e.event_id = '.$table.'.id')
        ->where("e.user_id", "=", ":user")
        ->params(":user",$user);
        return $this->find($id_obj);
    }

    public function findByOwner($owner)
    {
        $id_obj = $this->getIdObject();
        $id_obj->where('owner', '=', ':owner')
            ->params(':owner', $owner);
        return $this->find($id_obj);
    }
    
    public function include_owner_object($include_owner=false)
    {
        $this->_include_owner = $include_owner;
    }
    
    public function include_card_count($include_card_count=false)
    {
        $this->_include_card_count = $include_card_count;
    }
}