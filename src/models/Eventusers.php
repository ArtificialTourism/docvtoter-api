<?php
class Eventusers extends PHPFrame_PersistentObject
{
	
    public function __construct(array $options=null)
    {
    	$this->addField(
            "event_id",
            null,
            false,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "user_id",
            null,
            false,
            new PHPFrame_IntFilter()
        );
        
        parent::__construct($options);
    }
}
