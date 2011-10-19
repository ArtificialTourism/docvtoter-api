<?php
class Event extends PHPFrame_PersistentObject
{
	
    public function __construct(array $options=null)
    {
    	$this->addField(
            "name",
            null,
            false,
            new PHPFrame_StringFilter(array("max_length"=>100))
        );
        $this->addField(
            "description",
            null,
            true,
            new PHPFrame_StringFilter()
        );
        $this->addField(
            "summary",
            null,
            true,
            new PHPFrame_StringFilter()
        );
        $this->addField(
            "start",
            null,
            false,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "end",
            null,
            true,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "location_id",
            null,
            true,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "allow_anon",
            1,
            false,
            new PHPFrame_BoolFilter()
        );
        $this->addField(
            "auto_publish",
            1,
            false,
            new PHPFrame_BoolFilter()
        );
        $this->addField(
            "auto_close",
            1,
            false,
            new PHPFrame_BoolFilter()
        );
        $this->addField(
            "private",
            0,
            false,
            new PHPFrame_BoolFilter()
        );
        $this->addField(
            "password",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>128))
        );
        
        parent::__construct($options);
    }
}
