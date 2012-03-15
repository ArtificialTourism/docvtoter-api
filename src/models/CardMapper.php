<?php
class CardMapper extends PHPFrame_Mapper
{
    private $_include_owner = false;
    private $_event_id = false;
    private $_user_mapper, $_eventcard_mapper;
    private $_db;

	public function __construct(PHPFrame_Database $db)
    {
        parent::__construct("Card", $db, "#__card");

        $this->_user_mapper = new UserMapper($db);
        $this->_eventcard_mapper = new PHPFrame_Mapper('eventcards',$db);
        
        $this->_db = $db;
    }
    
    public function find(PHPFrame_IdObject $id_obj=null)
    {
    	if(!isset($id_obj)) {
    		$id_obj = $this->getIdObject();
    	}
    	$id_obj->where("status","=","'active'");
        $collection = parent:: find($id_obj);
        if($this->_event_id) {
        	//create map of card_id -> category_id
        	$card_category = array();
            //event_card id_obj:        	
            $ec_id_obj = $this->_eventcard_mapper->getIdObject();
            $ec_id_obj->where("event_id","=",":event_id");
            $params = array(":event_id"=>$this->_event_id);
            //get sql
            $sql = $ec_id_obj->getSQL();
            //fetch assoc array
            $assocList = $this->_db->fetchAssocList($sql, $params);
            
            foreach($assocList as $eventcard) {
            	$card_category[$eventcard['card_id']] = $eventcard['category_tag_id']; 
            }
        }

        foreach ($collection as $card) {
        	if ($this->_include_owner) {
                $owner = $this->_user_mapper->findOne($card->owner());
                $card->ownerUser($owner);
        	}
            
        	$card->eventCategoryId($card->category_id());
        	
        	if ($this->_event_id) {
        		if(isset($card_category[$card->id()]) && !empty($card_category[$card->id()]))
        		  $card->eventCategoryId($card_category[$card->id()]);
        	}
            $card->eventCategoryId($card->category_tag_id);
        }

        return $collection;
    }

    public function findOne($id_obj)
    {
        if (is_int($id_obj)){
            $id = $id_obj;
            $id_obj = $this->getIdObject();
            $id_obj->where('id', '=', ':id')
                ->params(':id', $id);
        }
        $collection = $this->find($id_obj);
        $collection->rewind();
        
        return $collection->current();
    }
    
    public function findBy($params)
    {
    	parse_str(http_build_query($params));
    	
    	$id_obj = $this->getIdObject();
        $table = $id_obj->getTableName();
        $id_obj->select($table.".*");
        $id_obj->where("status", "=", "'active'");
        if(isset($deck_id)) {
            $id_obj->join('JOIN #__deckcards d ON d.card_id = '.$table.'.id')
	        ->where("d.deck_id", "=", ":deck_id")
	        ->params(":deck_id",$deck_id);	
        }
        if(isset($event_id)) {
        	$this->_event_id = $event_id;
        	$id_obj->join('JOIN #__eventcards e ON e.card_id = '.$table.'.id')
            ->where("e.event_id", "=", ":event_id")
            ->params(":event_id",$event_id);
        }
        if(isset($owner)) {
        	$id_obj->where($table.'.owner', '=', ':owner')
            ->params(':owner', $owner);
        }
        if(isset($status)) {
        	$id_obj->where($table.'.status', '=', ':status')
        	->params(':status', $status);
        }
        if(isset($category_tag_id)) {
        	if(!$this->_event_id && !isset($event_id)) {
        	   	$category_id = $category_tag_id;
        	} else {
               //get event collection
               $event = $this->_event_mapper->findone($event_id);
               if(!isset($event) || !$event->id()) {
                   return null;
               }
               $collection_id = $event->collection_id();
               //join collectiontags
               $id_obj->join('JOIN #__collectiontags ct ON ct.card_id = '.$table.'.id')
               ->where("ct.category_tag_id","=",":category_tag_id")
               ->where("ct.collection_id","=",":collection_id")
               ->params(":category_tag_id",$category_tag_id)
               ->params(":collection_id",$collection_id);
        	}
        }
        if(isset($category_id)) {
            $id_obj->where($table.'.category_id', '=', ':category_id')
            ->params(':category_id', $category_id);
        }
        if(isset($tag_id)) {
	        $id_obj->join('JOIN cardtags t ON t.card_id = '.$table.'.id')
	        ->where("t.tag_id", "=", ":tag_id")
	        ->params(":tag_id",$tag_id);
	        if(isset($tag_user) && !empty($tag_user)) {
	            $id_obj->where("t.owner","=",":tag_user")
	            ->params(":tag_user",$tag_user);
	        }
        }

        $ret = $this->find($id_obj);
        //reset
        $this->_event_id = false;
        
        return $ret;
    }

//    public function findByDeckId($deck_id)
//    {
//        $id_obj = $this->getIdObject();
//        $table = $id_obj->getTableName();
//        $id_obj->select($table.".*")
//        ->join('JOIN deckcards d ON d.card_id = '.$table.'.id')
//        ->where("d.deck_id", "=", ":deck_id")
//        ->where("status", "=", "'active'")
//        ->params(":deck_id",$deck_id);
//        return $this->find($id_obj);
//    }
//    
//    public function findByEventId($event_id)
//    {
//    	$event_id = intval($event_id);
//        $id_obj = $this->getIdObject();
//        $table = $id_obj->getTableName();
//        $id_obj->select($table.".*")
//        ->join('JOIN #__eventcards e ON e.card_id = '.$table.'.id')
//        ->where("e.event_id", "=", ":event_id")
//        ->params(":event_id",$event_id);
//        $ret = $this->find($id_obj);
//
//        return $ret; 
//    }
    
//    public function findByOwner($user)
//    {
//    }
    
//    public function findByOrg($org)
//    {
//    }
    
//    public function findByTag($tag, $user=null)
//    {
//    	$id_obj = $this->getIdObject();
//        $table = $id_obj->getTableName();
//        $id_obj->select($table.".*")
//        ->join('JOIN cardtags t ON t.card_id = '.$table.'.id')
//        ->where("t.tag_id", "=", ":tag_id")
//        ->where("$table.status", "=", "'active'")
//        ->params(":tag_id",$tag);
//        if(isset($user) && !empty($user)) {
//        	$id_obj->where("t.owner","=",":user")
//        	->params(":user",$user);
//        }
//        return $this->find($id_obj);
//    }

//    public function findByOwner($owner)
//    {
//        $id_obj = $this->getIdObject();
//        $id_obj->where('owner', '=', ':owner')
//            ->params(':owner', $owner);
//
//        return $this->find($id_obj);
//    }

    public function include_owner_object($include_owner=false)
    {
        $this->_include_owner = $include_owner;
    }
    
    public function context_event($event_id=false)
    {
        $this->_event_id = $event_id;
    }
}