<?php
class DeckMapper extends PHPFrame_Mapper
{
	private $_include_card_count = false;
	private $_deckcard_mapper;
	private $_db;
	
	public function __construct(PHPFrame_Database $db)
    {
        parent::__construct("Deck", $db, "#__deck");
        
        $this->_deckcard_mapper = new PHPFrame_Mapper('deckcards',$db);
        $this->_db = $db;
    }
    
    public function find(PHPFrame_IdObject $id_obj=null)
    {
        $collection = parent:: find($id_obj);

        $cardMapper = new CardMapper($this->getFactory()->getDB());
        foreach ($collection as $deck){
        	$cards = $cardMapper->findByDeckId($deck->id());
        	foreach($cards as $card) {
        		$deck->addCard($card);
        	}
        	
            if ($this->_include_card_count && $deck->id()) {
                $id_obj = $this->_deckcard_mapper->getIdObject();
                $id_obj->select("COUNT(*)")
                ->where("deck_id","=",":deck_id");
                $params = array(":deck_id"=>$deck->id());
                $sql = $id_obj->getSQL();
                $assoc = $this->_db->fetchAssoc($sql, $params);
                $count = $assoc['COUNT(*)'];
                $deck->cardCount($count);
            }
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

    public function findByOwner($owner)
    {
        $id_obj = $this->getIdObject();
        $id_obj->where('owner', '=', ':owner')
            ->params(':owner', $owner);

        return $this->find($id_obj);
    }
    
    public function include_card_count($include_card_count=false)
    {
        $this->_include_card_count = $include_card_count;
    }
}