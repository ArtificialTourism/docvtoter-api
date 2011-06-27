<?php
class Category extends PHPFrame_PersistentObject
{
	
    public function __construct(array $options=null)
    {
    	$this->addField(
            "name",
            null,
            false,
            new PHPFrame_StringFilter(array("max_length"=>50))
        );
        $this->addField(
            "collection",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>50))
        );
        
        parent::__construct($options);
    }
}
