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

        $sql = "SELECT c.id as card_id, c.name as card_title, c.safe_name, CASE 
            WHEN e.category_tag_id IS NULL 
               THEN c.category_id 
               ELSE e.category_tag_id 
       END as category_tag_id, count($table.id) as total FROM $table";
        $sql .= " RIGHT JOIN eventcards e ON e.id = $table.eventcards_id";
        $sql .= " JOIN card c ON c.id = e.card_id";
        $sql .= " WHERE e.event_id = :event_id";
        $sql .= " GROUP BY card_id";
        $sql .= " ORDER BY c.id DESC";
        $params = array(":event_id"=>$event_id);
        $cards = $db->fetchAssocList($sql, $params);

        return $cards;
    }
}