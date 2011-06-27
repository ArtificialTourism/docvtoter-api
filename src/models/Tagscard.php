<?php
class Tagscard extends PHPFrame_PersistentObject
{
	
    public function __construct(array $options=null)
    {
    	$this->addField(
            "tag_id",
            null,
            false,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "card_id",
            null,
            false,
            new PHPFrame_IntFilter()
        );
        
        parent::__construct($options);
    }
}
