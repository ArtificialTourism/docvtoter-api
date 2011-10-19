<?php
class Tags extends PHPFrame_PersistentObject
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
            "type",
            "tag",
            false,
            new PHPFrame_StringFilter(array("max_length"=>64))
        );
        
        parent::__construct($options);
    }
}
