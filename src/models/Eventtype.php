<?php
class Eventtype extends PHPFrame_PersistentObject
{
	
    public function __construct(array $options=null)
    {
    	$this->addField(
            "name",
            null,
            false,
            new PHPFrame_StringFilter(array("max_length"=>100))
        );
        
        parent::__construct($options);
    }
}
