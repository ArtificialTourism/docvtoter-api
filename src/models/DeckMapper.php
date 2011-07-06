<?php
class DeckMapper extends PHPFrame_Mapper
{
	public function __construct(PHPFrame_Database $db)
    {
        parent::__construct("Deck", $db, "#__deck");
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
}