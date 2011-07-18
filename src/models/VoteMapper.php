<?php
class VoteMapper extends PHPFrame_Mapper
{
	private $_db;
	
	public function __construct(PHPFrame_Database $db)
    {
        parent::__construct("Vote", $db, "#__vote");
        
        $this->_db = $db;
    }
    
    public function findByEventId($event_id)
    {
        $db = $this->_db;
        $id_obj = $this->getIdObject();
        $table = $id_obj->getTableName();

        $sql = "SELECT c.id as card_id, c.name as card_title, c.safe_name, a.name as card_steep, count($table.id) as total FROM $table";
        $sql .= " JOIN eventcards e ON e.id = $table.eventcards_id";
        $sql .= " JOIN card c ON c.id = e.card_id";
        $sql .= " JOIN category a ON a.id = c.category_id";
        $sql .= " WHERE e.event_id = :event_id";
        $sql .= " GROUP BY $table.eventcards_id";

        $params = array(":event_id"=>$event_id);
        $cards = $db->fetchAssocList($sql, $params);

        return $cards;
    }
}