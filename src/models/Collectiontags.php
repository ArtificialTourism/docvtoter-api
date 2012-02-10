<?php
class Collectiontags extends PHPFrame_PersistentObject
{
	
    public function __construct(array $options=null)
    {
        $this->addField(
            "collection_id",
            null,
            false,
            new PHPFrame_IntFilter()
        );
    	$this->addField(
            "tag_id",
            null,
            false,
            new PHPFrame_IntFilter()
        );
        
        parent::__construct($options);
    }
}
