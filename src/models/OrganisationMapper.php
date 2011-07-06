<?php
class OrganisationMapper extends PHPFrame_Mapper
{
	private $_db;
	
	public function __construct(PHPFrame_Database $db)
    {
        parent::__construct("Organisation", $db, "#__organisation");
        
        $this->_db = $db;
    }
    
    public function find(PHPFrame_IdObject $id_obj=null)
    {
        $collection = parent:: find($id_obj);

        foreach ($collection as $org){
        	$db = $this->_db;
        	$table = $id_obj->getTableName();
	        
	        $sql = "SELECT id FROM $table WHERE parent_id = :id";
	        $params = array(":id"=>$org->id());
	        $children = $db->fetchAssocList($sql, $params);
	        $child_ids = array();
	        foreach($children as $child) {
	        	$child_ids[] = $child['id'];
	        } 
	        
            $org->child_ids($child_ids);
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