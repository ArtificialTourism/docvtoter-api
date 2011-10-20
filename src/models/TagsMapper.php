<?php
class TagsMapper extends PHPFrame_Mapper
{
	public function __construct(PHPFrame_Database $db)
    {
        parent::__construct("Tags", $db, "#__tags");
    }
    
    public function findByCard($card, $type=null)
    {
        $id_obj = $this->getIdObject();
        $table = $id_obj->getTableName();
        
        $id_obj->select($table.".*")
        ->join('JOIN tagscard t ON t.tag_id = '.$table.'.id')
        ->where("t.card_id", "=", ":card")
        ->params(":card",$card);
        if(isset($type) && !empty($type)) {
        	$id_obj->where("$table.type","=",":type")
        	->params(":type",$type);
        }
        
        return $this->find($id_obj);
    }
    
    public function findByNameType($name, $type)
    {
        $id_obj = $this->getIdObject();
        
        $id_obj->where("name", "=", ":name")
        ->where("type", "=", ":type")
        ->params(":name",$name)
        ->params(":type",$type);
        
        return $this->findOne($id_obj);
    }
    
    public function findByUser($user, $type=null)
    {
        $id_obj = $this->getIdObject();
        $table = $id_obj->getTableName();
        
        $id_obj->select($table.".*")
        ->join('JOIN tagscard t ON t.tag_id = '.$table.'.id')
        ->where("t.owner", "=", ":user")
        ->params(":user",$user);
        if(isset($type) && !empty($type)) {
            $id_obj->where("$table.type","=",":type")
            ->params(":type",$type);
        }
        
        return $this->find($id_obj);
    }
}