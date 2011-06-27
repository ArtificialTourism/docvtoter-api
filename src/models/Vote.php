<?php
class Vote extends PHPFrame_PersistentObject
{
	
    public function __construct(array $options=null)
    {
    	$this->addField(
            "eventcards_id",
            null,
            false,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "ip_address",
            null,
            true,
            new PHPFrame_StringFilter(array('max_length'=>15))
        );
        $this->addField(
            "city",
            null,
            true,
            new PHPFrame_StringFilter(array('max_length'=>50))
        );
        
        parent::__construct($options);
    }
}
