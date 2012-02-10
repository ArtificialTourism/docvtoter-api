<?php
class CardMapper extends PHPFrame_Mapper
{
    private $_include_owner = false;
    private $_event_id = false;
    private $_user_mapper, $_eventcard_mapper;

	public function __construct(PHPFrame_Database $db)
    {
        parent::__construct("Card", $db, "#__card");

        $this->_user_mapper = new UserMapper($db);
        $this->_eventcard_mapper = new PHPFrame_Mapper('eventcards',$db);
    }
    
    public function find(PHPFrame_IdObject $id_obj=null)
    {
    	$id_obj->where("status","=","'active'");
        $collection = parent:: find($id_obj);
        
        if($this->_event_id) {
        	//create map of card_id -> category_id
        	$card_category = array();
            //event_card id_obj:        	
            $ec_id_obj = $_eventcard_mapper->getIdObject();
            $ec_id_obj->where("event_id","=",":event_id")
            ->params(":event_id",$this->_event_id);
            //get sql
            $sql = $ec_id_obj->getSQL();
            //fetch assoc array
            $assocList = $db->fetchAssocList($sql);
            
            foreach($assocList as $eventcard) {
            	$card_category[$eventcard['card_id']] = $eventcard['category_tag_id']; 
            }
            
        }

        foreach ($collection as $card) {
        	if ($this->_include_owner) {
                $owner = $this->_user_mapper->findOne($card->owner());
                $card->ownerUser($owner);
        	}
            
        	if ($this->_event_id) {
        		if(isset($card_category[$card_id]) && !empty($card_category[$card_id]))
        		  $card->eventCategoryId($card_category[$card_id]);
        	}
        	
            $card->eventCategoryId($card->category_id);
        }

        return $collection;
    }

    public function findOne($id_obj)
    {
        $card = parent:: findOne($id_obj);

        if($card && $card->id()) {
	        if ($this->_include_owner) {
	            $owner = $this->_user_mapper->findOne($card->owner());
	            $card->ownerUser($owner);
	        }
        
            $card->eventCategoryId($card->category_id());
        }
        
        return $card;
    }

    public function findByDeckId($deck_id)
    {
        $id_obj = $this->getIdObject();
        $table = $id_obj->getTableName();
        $id_obj->select($table.".*")
        ->join('JOIN deckcards d ON d.card_id = '.$table.'.id')
        ->where("d.deck_id", "=", ":deck_id")
        ->where("status", "=", "'active'")
        ->params(":deck_id",$deck_id);
        return $this->find($id_obj);
    }
    
    public function findByEventId($event_id)
    {
    	$event = $this->_event_mapper()->fetch($event_id);
    	
        $id_obj = $this->getIdObject();
        $table = $id_obj->getTableName();
        $id_obj->select($table.".*")
        ->join('JOIN #__eventcards e ON e.card_id = '.$table.'.id')
        ->where("e.event_id", "=", ":event_id")
        ->params(":event_id",$event_id);
        $ret = $this->find($id_obj);

        return $ret; 
    }
    
//    public function findByOwner($user)
//    {
//    }
    
//    public function findByOrg($org)
//    {
//    }
    
    public function findByTag($tag, $user=null)
    {
    	$id_obj = $this->getIdObject();
        $table = $id_obj->getTableName();
        $id_obj->select($table.".*")
        ->join('JOIN cardtags t ON t.card_id = '.$table.'.id')
        ->where("t.tag_id", "=", ":tag_id")
        ->where("$table.status", "=", "'active'")
        ->params(":tag_id",$tag);
        if(isset($user) && !empty($user)) {
        	$id_obj->where("t.owner","=",":user")
        	->params(":user",$user);
        }
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
    
    public function context_event($event_id=false)
    {
        $this->_event_id = $event_id;
    }
}