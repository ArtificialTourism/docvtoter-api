<?php
class Card extends PHPFrame_PolymorphicPersistentObject
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
            "safe_name",
            null,
            false,
            new PHPFrame_StringFilter(array("max_length"=>50))
        );
        $this->addField(
            "category_id",
            null,
            false,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "topic_id",
            null,
            false,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "question",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>255))
        );
        $this->addField(
            "factoid",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>1023))
        );
        $this->addField(
            "description",
            null,
            true,
            new PHPFrame_StringFilter()
        );
        $this->addField(
            "image",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>255))
        );
        $this->addField(
            "card_front",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>255))
        );
        $this->addField(
            "card_back",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>255))
        );
        $this->addField(
            "origin_event_id",
            null,
            true,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "uri",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>255))
        );
        
        parent::__construct($options);
    }
}
