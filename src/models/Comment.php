<?php
class Comment extends PHPFrame_PersistentObject
{
	
    public function __construct(array $options=null)
    {
    	$this->addField(
            "card_id",
            null,
            false,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "message",
            null,
            false,
            new PHPFrame_StringFilter()
        );
        
        parent::__construct($options);
    }
}
