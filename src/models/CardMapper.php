<?php
class CardMapper extends PHPFrame_Mapper
{
    private $_include_owner = false;
    private $_user_mapper;

	public function __construct(PHPFrame_Database $db)
    {
        parent::__construct("Card", $db, "#__card");

        $this->_user_mapper = new UserMapper($db);
    }
    
    public function find(PHPFrame_IdObject $id_obj=null)
    {
    	$id_obj->where("status","=","'active'");
        $collection = parent:: find($id_obj);

        if ($this->_include_owner) {
            foreach ($collection as $card) {
                $owner = $this->_user_mapper->findOne($card->owner());
                $card->ownerUser($owner);
            }
        }

        return $collection;
    }

    public function findOne($id_obj)
    {
        $card = parent:: findOne($id_obj);

        if ($this->_include_owner) {
            $owner = $this->_user_mapper->findOne($card->owner());
            $card->ownerUser($owner);
        }
        
        return $card;
    }

    public function findByDeckId($deck_id)
    {
        $id_obj = $this->getIdObject();
        $table = $id_obj->getTableName();
        $id_obj->select($table.".*")
        ->join('JOIN cardstodeck d ON d.card_id = '.$table.'.id')
        ->where("d.deck_id", "=", ":deck_id")
        ->where("status", "=", "'active'")
        ->params(":deck_id",$deck_id);
        return $this->find($id_obj);
    }
    
    public function findByEventId($event_id)
    {
        $id_obj = $this->getIdObject();
        $table = $id_obj->getTableName();
        $id_obj->select($table.".*")
        ->join('JOIN eventcards e ON e.card_id = '.$table.'.id')
        ->where("e.event_id", "=", ":event_id")
        ->where("status", "=", "'active'")
        ->params(":event_id",$event_id);
        return $this->find($id_obj);
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
        ->join('JOIN tagscard t ON t.card_id = '.$table.'.id')
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
}