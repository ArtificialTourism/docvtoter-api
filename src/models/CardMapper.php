<?php
class CardMapper extends PHPFrame_Mapper
{
	public function __construct(PHPFrame_Database $db)
    {
        parent::__construct("Card", $db, "#__card");
    }
    
    public function findByDeckId($deck_id)
    {
        $id_obj = $this->getIdObject();
        $table = $id_obj->getTableName();
        $id_obj->select($table.".*")
        ->join('JOIN cardstodeck d ON d.card_id = '.$table.'.id')
        ->where("d.deck_id", "=", ":deck_id")
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
        ->params(":event_id",$event_id);
        return $this->find($id_obj);
    }
}