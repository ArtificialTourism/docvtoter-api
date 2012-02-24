<?php
class CollectionMapper extends PHPFrame_Mapper
{
	private $_category_mapper;
	
	public function __construct(PHPFrame_Database $db)
    {
        parent::__construct("Collection", $db, "#__collection");
        
        $this->_tags_mapper = new TagsMapper($db);
        
    }
    
    public function find(PHPFrame_IdObject $id_obj=null)
    {
        $collections = parent:: find($id_obj);

        foreach ($collections as $collection) {
        	$id_obj = $this->_tags_mapper->getIdObject();
        	$id_obj->select("#__tags.*")
        	->join("LEFT JOIN #__collectiontags ON #__collectiontags.tag_id = #__tags.id")
        	->join("LEFT JOIN #__collection ON #__collection.id = #__collectiontags.collection_id")
        	->where("collection_id","=",":collection_id")
        	->params(":collection_id",$collection->id());
	        $categories = $this->_tags_mapper->find($id_obj); 
	        $collection->categories($categories);
        } 

        return $collections;
    }

    public function findOne($id_obj)
    {
        if (is_numeric($id_obj)){
            $id = intval($id_obj);
            $id_obj = $this->getIdObject();
            $id_obj->where('id', '=', ':id')
                ->params(':id', $id);
        }
        $collection = $this->find($id_obj);
        $collection->rewind();
        
        return $collection->current();
    }

    public function findByName($name)
    {
        $id_obj = $this->getIdObject();
        $id_obj->where('name', '=', ':name')
            ->params(':name', $name);

        return $this->find($id_obj);
    }
}